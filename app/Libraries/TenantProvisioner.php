<?php

namespace App\Libraries;

/*
 * 신규 상점(fid) DB를 실제로 만드는 라이브러리 - CREATE DATABASE, 스키마 템플릿(app/sql/
 * tenant_schema_template.sql) 실행, 필수 기본데이터(app/sql/tenant_default_data.sql:
 * menu/function_menu/basic) 실행, basic의 placeholder 행을 실제 신청정보로 갱신,
 * 업로드 디렉토리 생성, 그리고 중앙 bomul.member에 로그인 계정 등록까지 한 번에 처리한다.
 * (member는 LoginModel::adminLogin()이 조회하는, 중개사가 bo 로그인할 때 쓰는 테이블)
 *
 * setting_callback.php가 setting_queue의 PENDING 건을 처리할 때 호출한다.
 *
 * db_connect('default')를 그대로 못 쓴다 - 그 커넥션은 이 스크립트가 다루는 중앙 bomul DB(setting_queue
 * 등)에 물려있는데, 여기서 USE `{fid}`를 해버리면 같은 커넥션을 쓰는 다른 코드까지 DB가 바뀐다.
 * 그래서 테넌트 DB 생성/스키마 작업엔 별도 mysqli 커넥션을 새로 열어서 쓰고, member 등록은
 * (중앙 DB 작업이라) db_connect('default')를 그대로 쓴다.
 */
class TenantProvisioner
{
    public function provision(string $fid, array $payload): array
    {
        if (!preg_match('/^[a-z0-9][a-z0-9-]{1,30}[a-z0-9]$/', $fid)) {
            return ['ok' => false, 'message' => "fid 형식이 올바르지 않습니다: {$fid}"];
        }

        $dbConfig = config('Database')->default;
        $mysqli = @new \mysqli($dbConfig['hostname'], $dbConfig['username'], $dbConfig['password'], '', (int) ($dbConfig['port'] ?? 3306));
        if ($mysqli->connect_errno) {
            return ['ok' => false, 'message' => 'DB 서버 연결 실패: ' . $mysqli->connect_error];
        }

        $dbAlreadyExisted = false;
        try {
            // 이미 존재하면(콜백 실패 후 재시도 등) DB 생성/스키마/기본데이터는 건드리지 않는다 -
            // 매번 새로 밀면 그 사이 상점 관리자가 입력해둔 값이 날아간다. 다만 member 등록은
            // 별개 단계라 여기서 return하지 않고 아래까지 계속 진행한다 - DB는 만들어졌는데
            // member 등록 전에 죽은 재시도 케이스를 여기서 건너뛰면 영영 로그인 계정이 안 생긴다.
            $escapedFid = $mysqli->real_escape_string($fid);
            $exists = $mysqli->query("SHOW DATABASES LIKE '{$escapedFid}'");
            $dbAlreadyExisted = $exists && $exists->num_rows > 0;

            if (!$dbAlreadyExisted) {
                if (!$mysqli->query("CREATE DATABASE `{$fid}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
                    return ['ok' => false, 'message' => 'DB 생성 실패: ' . $mysqli->error];
                }
                if (!$mysqli->select_db($fid)) {
                    return ['ok' => false, 'message' => 'DB 선택 실패: ' . $mysqli->error];
                }

                $this->runSqlFile($mysqli, ROOT_PATH . '/app/sql/tenant_schema_template.sql');
                $this->runSqlFile($mysqli, ROOT_PATH . '/app/sql/tenant_default_data.sql');
                $this->updateBasicRow($mysqli, $payload);
                $this->createDirectories($fid);
            }
        } finally {
            $mysqli->close();
        }

        $memberResult = $this->ensureMemberRow($fid, $payload);
        if (!$memberResult['ok']) {
            return $memberResult;
        }

        return ['ok' => true, 'message' => ($dbAlreadyExisted ? "이미 존재하는 DB" : "설치 완료") . "({$fid}) / {$memberResult['message']}"];
    }

    // 중앙 bomul.member에 로그인 계정 등록 - customer_pw는 이미 SHA-256 해시라 재해시 없이 그대로
    // 복사한다 (bomul-bo CustomerModel과 동일 알고리즘 전제). fid로 이미 등록돼 있으면 재등록 안 함
    // (재시도 시 중복 INSERT 방지).
    private function ensureMemberRow(string $fid, array $payload): array
    {
        $userId  = (string) ($payload['customer_id'] ?? '');
        $userPwd = (string) ($payload['customer_pw'] ?? '');
        if ($userId === '' || $userPwd === '') {
            return ['ok' => false, 'message' => 'member 등록 실패 - payload에 customer_id/customer_pw가 없습니다'];
        }

        $db = db_connect('default');
        $model = model(\App\Models\MemberModel::class, true, $db);

        if ($model->existsByFid($fid)) {
            return ['ok' => true, 'message' => 'member 이미 등록됨'];
        }
        if ($model->existsByUserId($userId)) {
            return ['ok' => false, 'message' => "member 등록 실패 - user_id 중복({$userId})"];
        }

        $saved = $model->sql_save([
            'user_id'  => $userId,
            'user_pwd' => $userPwd,
            'fid'      => $fid,
        ]);

        return $saved
            ? ['ok' => true, 'message' => 'member 등록 완료']
            : ['ok' => false, 'message' => 'member 등록 실패 - ' . ($model->getDbError()['message'] ?? '')];
    }

    // mysqldump 스타일 파일 실행 - CI4 쿼리빌더는 한 번에 한 문장만 돌리는 게 기본이라, 세미콜론+개행을
    // 문장 경계로 본다. 블록 주석(/* ... */) 안에 세미콜론이 섞여있으면(Navicat 덤프 헤더 등에 흔함)
    // 그 경계에서 잘못 잘려서 주석 뒷부분이 SQL 문장으로 실행돼버리는 문제가 있었다 - 그래서 쪼개기
    // 전에 블록/라인 주석을 전부 제거한다 (문자열 리터럴 안에 "--"나 "/*"가 있는 경우는 지원 안 함 -
    // 이 파일들은 스키마/시드 데이터 덤프라 그런 값이 안 들어갈 걸로 가정).
    private function runSqlFile(\mysqli $mysqli, string $path): void
    {
        $sql = file_get_contents($path);
        $sql = preg_replace('#/\*.*?\*/#s', '', $sql);   // 블록 주석 제거 (여러 줄 걸쳐도 통째로)
        $sql = preg_replace('/^--.*$/m', '', $sql);       // 라인 주석 제거
        $statements = preg_split('/;\s*\n/', $sql);

        foreach ($statements as $statement) {
            $statement = trim($statement);
            if ($statement === '') {
                continue;
            }
            // PHP 8.1+ 기본 mysqli 에러모드는 실패 시 false를 반환하지 않고 mysqli_sql_exception을
            // 던진다 - 그래서 잡아서 로그만 남기고 계속 진행한다 (한 문장 실패로 나머지 시드 데이터
            // 전체가 안 들어가는 걸 막기 위함). setting_callback.php는 이 예외를 안 잡아서, 안 잡으면
            // 배치 전체가 죽고 markResult()도 못 불려서 같은 건이 매분 재시도-크래시를 반복하게 된다.
            try {
                $mysqli->query($statement);
            } catch (\mysqli_sql_exception $e) {
                log_message('error', '[TenantProvisioner] SQL 실패: ' . $e->getMessage() . ' / stmt: ' . substr($statement, 0, 100));
            }
        }
    }

    // tenant_default_data.sql이 이미 basic_no=1로 placeholder 기본행을 넣어놨다 - 여기서는 그 행을
    // 실제 신청 정보로 UPDATE만 한다 (INSERT하면 중복행이 생김). 색상/노출여부/주소/전화 등
    // 나머지 placeholder 값은 그대로 두고, 상호/대표자/등록번호만 덮어쓴다.
    private function updateBasicRow(\mysqli $mysqli, array $payload): void
    {
        $companyName = $mysqli->real_escape_string((string) ($payload['company_name'] ?? ''));
        $ceoName     = $mysqli->real_escape_string((string) ($payload['ceo_name'] ?? ''));
        $licenseNo   = $mysqli->real_escape_string((string) ($payload['license_no'] ?? ''));

        $sql = "UPDATE `basic` SET
                    `site_name` = '{$companyName}',
                    `company_name` = '{$companyName}',
                    `broker_office_name` = '{$companyName}',
                    `broker_reg_no` = '{$licenseNo}',
                    `broker_ceo_name` = '{$ceoName}'
                WHERE `basic_no` = 1";

        if (!$mysqli->query($sql)) {
            log_message('error', '[TenantProvisioner] basic update 실패: ' . $mysqli->error);
        }
    }

    private function createDirectories(string $fid): void
    {
        foreach (['', 'basic/', 'menu/', 'property/'] as $sub) {
            $dir = APP_PDS . $fid . '/' . $sub;
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
    }
}
