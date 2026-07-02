<tr class="table-second">
    <th>Menu</th>
    <td>list</td>
    <td>메뉴</td>
    <td>예) <a href="<?=SITE?>/Menu" target="_blank"><?=SITE?>/Menu</a></td>
</tr>
<tr>
    <td>JSON</td>
    <td class="table-info align-middle justify-content-end" align="right">응답</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>SUCCESS</td>
                <td>String</td>
                <td>성공여부(TRUE,FALSE)</td>
            </tr>
            <tr>
                <td>MSG</td>
                <td>String</td>
                <td>메시지(실패시 실패메시지, 성공시엔 성공입니다.)</td>
            </tr>
            <tr>
                <td>RECORD</td>
                <td>Array</td>
                <td>배열데이터(data)</td>
            </tr>
        </table>
    </td>
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
                <td>menu_no</td>
                <td>Integer</td>
                <td>키값</td>
            </tr>
            <tr>
                <td>name</td>
                <td>String(max:4)</td>
                <td>메뉴명(식별용)</td>
            </tr>
            <tr>
                <td>code</td>
                <td>String(max:32)</td>
                <td>식별코드</td>
            </tr>
            <tr>
                <td>menu_show</td>
                <td>String(max:128)</td>
                <td>이름</td>
            </tr>
            <tr>
                <td>bgcolor</td>
                <td>String(max:8)</td>
                <td>배경 색상(#ffffff)</td>
            </tr>
            <tr>
                <td>title</td>
                <td>String(max:128)</td>
                <td>타이틀문구내용</td>
            </tr>
            <tr>
                <td>title_show</td>
                <td>String(max:8)</td>
                <td>타이틀문구 숨김,보임( N,Y)</td>
            </tr>
            <tr>
                <td>bgcolor_show</td>
                <td>String(max:8)</td>
                <td>배경앞 투명배경색(Y,N)</td>
            </tr>
            <tr>
                <td>write_date</td>
                <td>String</td>
                <td>등록일</td>
            </tr>

        </table>
    </td>
</tr>