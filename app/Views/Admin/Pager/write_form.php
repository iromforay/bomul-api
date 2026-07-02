<div class="bs-callout bs-callout-danger">
    <h1>페이지 생성</h1>
</div>

<table class="table table-hover table-dark">
    <tbody>
    <form class="px-md-2" name="pager" action="<?=Admin_SITE?>/Pager/register" method="POST">
        <tr>
            <th scope="row" >코드 <sub>*자동생성</sub></th>
            <td class="table-primary" type="text">
                <input type="hiddens" name="code"  value="<?=$code?>" readonly/>
            </td>
        </tr>
        <tr>
            <th scope="row" >이름</th>
            <td class="table-primary" type="text">
                <input type="text" name="title"  class="form-control" />
            </td>
        </tr>
        <tr>
            <th scope="row" >스타일</th>
            <td class="table-primary" type="text">
                <input type="radio" name="style" value="1" checked> 이미지1(기본스타일)
                <input type="radio" name="style" value="2"> 이미지2(00스타일)
                <input type="radio" name="style" value="3"> 이미지3(00스타일)

            </td>
        </tr>
        <tr>
            <th scope="row" >읽기 권한</th>
            <td class="table-primary" type="text">
                <input type="radio" name="reader" value="member"> 회원만 공개
                <input type="radio" name="reader" value="every" checked> 모두공개 (회원 +비회원)
            </td>
        </tr>
    </form>
    </tbody>
</table>
<div class="mb-2">
    <span class="btn btn-primary" onclick="document.pager.submit()">등록</span>
    <span class="btn btn-warning" onclick="location.href='<?=Admin_SITE."/Boards/list"?>'">취소</span>
</div>
<style>
    textarea{width:98%;}
</style>