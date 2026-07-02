<tr class="table-success">
    <th>Page</th>
    <td>Data</td>
    <td>페이지 정보</td>
    <td>예) <a href="<?=SITE?>/Page/data?code=" target="_blank"><?=SITE?>/Page/data?code=</a> </td>
</tr>
<tr>
    <td class="align-middle">GET</td>
    <td class="table-info align-middle"  align="right">요청</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>code</td>
                <td>String</td>
                <td>코드값</td>
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
                <td>SUCCESS</td>
                <td>String(max:5)</td>
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
                <td>배열데이터(row,data)</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td class="align-middle">JSON</td>
    <td class="table-info align-middle"  align="right">row</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>pager_no</td>
                <td>String</td>
                <td>키값</td>
            </tr>
            <tr>
                <td>title</td>
                <td>String</td>
                <td>제목</td>
            </tr>
            <tr>
                <td>status</td>
                <td>String</td>
                <td>상태(T:정상, F:삭제 )</td>
            </tr>
            <tr>
                <td>code</td>
                <td>String</td>
                <td>페이지 접근코드</td>
            </tr>
            <tr>
                <td>style</td>
                <td>String</td>
                <td>적용스타일</td>
            </tr>
            <tr>
                <td>reader</td>
                <td>String</td>
                <td>읽기 권한(every:모두, member:회원전용)</td>
            </tr>
            <tr>
                <td>write_date</td>
                <td>String</td>
                <td>등록일(Y-m-d H:i:s)</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td class="align-middle">JSON</td>
    <td class="table-info align-middle"  align="right">data</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>pager_dno</td>
                <td>String(max:5)</td>
                <td>키값</td>
            </tr>
            <tr>
                <td>pager_no</td>
                <td>String</td>
                <td>row.pager_no</td>
            </tr>
            <tr>
                <td>body</td>
                <td>String</td>
                <td>내용</td>
            </tr>
            <tr>
                <td>sorty</td>
                <td>String</td>
                <td>순서</td>
            </tr>
            <tr>
                <td>status</td>
                <td>String</td>
                <td>상태(T:정상, F:미노출)</td>
            </tr>
            <tr>
                <td>write_date</td>
                <td>String</td>
                <td>등록일(Y-m-d H:i:s)</td>
            </tr>
        </table>
    </td>
</tr>