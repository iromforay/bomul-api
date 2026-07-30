<?php

namespace App\Libraries;

/*
 * 네이버 지오코딩 API 공용 호출 - Admin\Map(주소검색 API)과 PropertyService(매물 저장 시
 * 자동 위경도 등록) 둘 다 같은 로직을 쓰므로 여기 하나로 모았다.
 */
class NaverGeocoder
{
    // 주소 문자열 → ['lat'=>..., 'lng'=>...] 또는 검색 실패 시 null
    public static function geocode($address)
    {
        if (empty($address)) {
            return null;
        }

        $url = 'https://naveropenapi.apigw.ntruss.com/map-geocode/v2/geocode?query=' . urlencode($address);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => self::authHeaders(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);
        $body = curl_exec($ch);
        curl_close($ch);

        $geo = json_decode($body, true) ?? [];
        if (($geo['status'] ?? '') !== 'OK' || empty($geo['addresses'])) {
            return null;
        }

        $first = $geo['addresses'][0];
        return ['lat' => $first['y'], 'lng' => $first['x']];
    }

    // 정적 지도 이미지 원본 바이트 (Admin\Map::getImage()가 그대로 프록시)
    public static function fetchStaticMapImage($lat, $lng)
    {
        $url = 'https://naveropenapi.apigw.ntruss.com/map-static/v2/raster'
            . '?w=300&h=300&center=' . $lng . ',' . $lat . '&level=16'
            . '&markers=type:d|size:mid|pos:' . $lng . '%20' . $lat;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => self::authHeaders(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);
        $body = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode === 200 ? $body : null;
    }

    private static function authHeaders()
    {
        return [
            'X-NCP-APIGW-API-KEY-ID: ' . ($_ENV['map.ClientID'] ?? ''),
            'X-NCP-APIGW-API-KEY: ' . ($_ENV['map.Secret'] ?? ''),
        ];
    }
}
