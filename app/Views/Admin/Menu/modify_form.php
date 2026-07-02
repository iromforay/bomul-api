<script>
    $(document).ready(function (){
        $("input:radio[name='link_type']:radio[value='<?=$data['link_type']?>']").prop("checked",true);
        $("input:radio[name='depth']:radio[value='<?=$data['depth']?>']").prop("checked",true);
    });

</script>
<div class="bs-callout bs-callout-danger">
    <h1>메뉴 수정</h1>
</div>

<table class="table table-hover table-dark">
    <tbody>
    <form class="px-md-2" name="pager" action="<?=Admin_SITE?>/Menu/modify" method="POST">
        <input type="hidden" name="menu_no" value ="<?=$data['menu_no']?>" >
        <tr>
            <th scope="row" >메뉴명</th>
            <td class="table-primary" type="text">
                <input type="text" name="name"  class="form-control" value="<?=$data['name']?>"/>
            </td>
        </tr>
        <tr>
            <th scope="row" >링크타입</th>
            <td class="table-primary" type="text">
                <input type="radio" name="link_type" value="bo" checked> 게시판
                <input type="radio" name="link_type" value="pa"> 페이지
                <input type="radio" name="link_type" value="re"> 외부링크

            </td>
        </tr>
        <tr>
            <th scope="row" >링크</th>
            <td class="table-primary" type="text">
                <input type="text" name="link"  class="form-control" value="<?=$data['link']?>"/>
            </td>
        </tr>
        <tr>
            <th scope="row" >메뉴이미지</th>
            <td class="table-primary" type="text">
                이미지 <input type="file" name="img" > <br/>
                이미지(마우스오버) <input type="file" name="img_over" >
            </td>
        </tr>
        <tr>
            <th scope="row" >메뉴깊이</th>
            <td class="table-primary" type="text">
                <input type="radio" name="depth" value="1" checked> 대메뉴
                <input type="radio" name="depth" value="2"> 중메뉴

            </td>
        </tr>
        <tr id="p_menu">
            <th scope="row" >부모메뉴 코드</th>
            <td class="table-primary" type="text">
                <input type="text" name="p_menu_no"  class="form-control" value="<?=$data['p_menu_no']?>"/>
            </td>
        </tr>
    </form>
    </tbody>
</table>
<div class="mb-2">
    <span class="btn btn-primary" onclick="document.pager.submit()">등록</span>
    <span class="btn btn-warning" onclick="location.href='<?=Admin_SITE."/Menu/list"?>'">취소</span>
</div>
<style>
    #p_menu{display: none;}
    textarea{width:98%;}
</style>