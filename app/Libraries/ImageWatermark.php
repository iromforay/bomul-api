<?php

namespace App\Libraries;

/*
 * 이미지 파일에 텍스트 워터마크(부동산명)를 직접 그려서 그 자리에 덮어쓴다.
 * 매물 이미지 업로드(Admin\Property::postUpload_images()) 전용 - File_Upload/ImgService는
 * 이미지 말고도 다른 파일(pdf/hwp/doc 등)도 다루는 범용 클래스라 거기 넣지 않고 여기서 처리한다.
 *
 * 한글 워터마크를 그리려면 한글 글자가 있는 TTF 폰트 파일이 필요하다(GD 내장 폰트는 영문/숫자만
 * 지원) - app/Libraries/fonts/watermark.ttf 경로에 폰트 파일을 넣어야 동작한다 (예: 나눔고딕 등
 * 무료 배포 폰트). 폰트 파일이 없거나 GD 확장이 없으면 워터마크를 조용히 건너뛴다 - 워터마크
 * 실패로 매물 이미지 업로드 자체가 깨지면 안 되기 때문이다.
 */
class ImageWatermark
{
    private const FONT_PATH = APPPATH . 'Libraries/fonts/watermark.ttf';

    // 성공/스킵 여부만 반환 - 실패해도 예외를 던지지 않는다(호출부가 업로드 성공 여부와
    // 별개로 처리할 수 있게).
    public static function apply($filePath, $text)
    {
        if ($text === '' || $text === null) {
            return false;
        }
        if (!function_exists('imagecreatefromstring') || !function_exists('imagettftext')) {
            return false; // GD(또는 GD의 FreeType 지원) 없음
        }
        if (!is_file(self::FONT_PATH)) {
            return false; // 한글 폰트 파일 미배치
        }
        if (!is_file($filePath)) {
            return false;
        }

        $raw = @file_get_contents($filePath);
        if ($raw === false) {
            return false;
        }
        $image = @imagecreatefromstring($raw);
        if ($image === false) {
            return false; // 이미지 파일이 아니거나(pdf/doc 등) 손상됨
        }

        imagesavealpha($image, true);
        imagealphablending($image, true);

        $width = imagesx($image);
        $height = imagesy($image);
        $fontSize = max(12, (int)round($width / 32));
        $margin = max(10, (int)round($width / 60));

        $box = imagettfbbox($fontSize, 0, self::FONT_PATH, $text);
        $textWidth = abs($box[4] - $box[0]);
        $x = max($margin, $width - $textWidth - $margin);
        $y = $height - $margin;

        // 가독성을 위해 그림자(검정, 반투명) 먼저, 그 위에 흰색 반투명 본문
        $shadow = imagecolorallocatealpha($image, 0, 0, 0, 55);
        $white = imagecolorallocatealpha($image, 255, 255, 255, 45);
        imagettftext($image, $fontSize, 0, $x + 1, $y + 1, $shadow, self::FONT_PATH, $text);
        imagettftext($image, $fontSize, 0, $x, $y, $white, self::FONT_PATH, $text);

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $saved = match ($ext) {
            'png' => imagepng($image, $filePath),
            'gif' => imagegif($image, $filePath),
            default => imagejpeg($image, $filePath, 90), // jpg/jpge 등
        };

        imagedestroy($image);
        return $saved;
    }
}
