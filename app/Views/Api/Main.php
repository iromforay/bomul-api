<tr class="table-primary">
    <th>Main</th>
    <td>index</td>
    <td>메인 화면 구성정보</td>
    <td>예) <a href="<?=SITE?>/Main" target="_blank"><?=SITE?>/Main</a></td>
</tr>
<tr>
    <td class="align-middle">JSON</td>
    <td class="table-info align-middle"  align="right">응답</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>build_no</td>
                <td>Integer</td>
                <td>키값</td>
            </tr>
            <tr>
                <td>uses</td>
                <td>String(max:4)</td>
                <td>사용여부 (Y,N)</td>
            </tr>
            <tr>
                <td>code</td>
                <td>String(max:32)</td>
                <td>(위치)식별코드</td>
            </tr>
            <tr>
                <td>build_title</td>
                <td>String(max:128)</td>
                <td>이름</td>
            </tr>
            <tr>
                <td>type</td>
                <td>String(max:8)</td>
                <td>타입 (text,image)</td>
            </tr>
            <tr>
                <td>img_url</td>
                <td>String(max:128)</td>
                <td>이미지 파일 FULL URL</td>
            </tr>
            <tr>
                <td>bg_color</td>
                <td>String(max:8)</td>
                <td>백그라운드 색상(#ffffff)</td>
            </tr>
            <tr>
                <td>color</td>
                <td>String(max:8)</td>
                <td>폰트색상(#000000)</td>
            </tr>
            <tr>
                <td>text</td>
                <td>String(max:128)</td>
                <td>내용</td>
            </tr>
            <tr>
                <td>link_type</td>
                <td>String(max:16)</td>
                <td>링크타입(bo,pa,re,no)<br>
                    bo: 게시판 연결
                    pa : 페이지 연결
                    re : 외부 연결
                    no : 링크 없음
                </td>
            </tr>
            <tr>
                <td>link</td>
                <td>String(max:256)</td>
                <td>링크값(bo는 board_code 참조)</td>
            </tr>
            <tr>
                <td>show_count</td>
                <td>Integer</td>
                <td>게시물 노출수량</td>
            </tr>
            <tr>
                <td>write_date</td>
                <td>String</td>
                <td>등록일</td>
            </tr>
            <tr>
                <td>code</td>
                <td>String(max:64)</td>
                <td>link_type :bo,pa 일때 연동할 코드</td>
            </tr>

        </table>
    </td>
</tr>