<?php

namespace App\Controllers\Web;

use CodeIgniter\Controller;

/*
 * /robots.txt - 중개사 서브도메인마다 자기 sitemap.xml을 가리켜야 해서 정적 파일이 아니라
 * 동적으로 생성한다 (public/robots.txt는 삭제함 - 있으면 nginx가 그 정적 파일을 먼저 서빙해서
 * 이 컨트롤러까지 요청이 안 옴).
 *
 * Web\BaseController를 상속하지 않은 이유: Track.php와 동일 - get_header()/body()가 항상
 * DB 조회를 하는데, robots.txt처럼 매 크롤러 요청마다 불리는 가벼운 엔드포인트엔 불필요하다.
 * SHOP_CODE도 필요 없다 - sitemap URL을 만드는 데 필요한 건 Host 헤더뿐이라 DB 자체를
 * 안 만진다.
 */
class Robots extends Controller
{
    public function getIndex()
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';

        $body = "User-agent: *\n";
        $body .= "Allow: /\n";
        $body .= "\n";
        $body .= "Sitemap: http://{$host}/sitemap.xml\n";

        return $this->response->setContentType('text/plain')->setBody($body);
    }
}
