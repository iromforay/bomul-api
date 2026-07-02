<tr class="table-danger">
    <th>Board</th>
    <td>index</td>
    <td>게시판 정보</td>
    <td>예) <a href="<?=SITE?>/Board?code=" target="_blank"><?=SITE?>/Board?code=</a></td>
</tr>
<tr>
    <td>GET</td>
    <td class="table-info align-middle justify-content-end" align="right">요청</td>
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
                <td>게시판 코드</td>
            </tr>
        </table>
    </td>
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
                <td>배열데이터(board_info,permission)</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td>JSON</td>
    <td class="table-info align-middle justify-content-end" align="right">board_info</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>header_text</td>
                <td>String</td>
                <td>상단텍스트</td>
            </tr>
            <tr>
                <td>header_image</td>
                <td>String</td>
                <td>상단이미지</td>
            </tr>
            <tr>
                <td>footer_text</td>
                <td>String</td>
                <td>하단텍스트</td>
            </tr>
            <tr>
                <td>footer_image</td>
                <td>String</td>
                <td>하단이미지</td>
            </tr>
            <tr>
                <td>name</td>
                <td>String</td>
                <td>게시판명</td>
            </tr>
            <tr>
                <td>perpage</td>
                <td>String</td>
                <td>페이지당 게시물수</td>
            </tr>
            <tr>
                <td>style</td>
                <td>String</td>
                <td>적용스타일  </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td>JSON</td>
    <td class="table-info align-middle justify-content-end" align="right">permission</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>write</td>
                <td>String</td>
                <td>쓰기권한(admin:관리자만, member: 회원만, every:누구나)</td>
            </tr>
            <tr>
                <td>read</td>
                <td>String</td>
                <td>읽기권한 (self: 자기글만 ,member: 회원만, every:누구나)</td>
            </tr>
            <tr>
                <td>list</td>
                <td>String</td>
                <td>리스트 보기 권한 (self: 자기글만, every:누구나)</td>
            </tr>
            <tr>
                <td>reply</td>
                <td>String(max:1)</td>
                <td>댓글기능가능 (Y,N)</td>
            </tr>
            <tr>
                <td>secret</td>
                <td>String(max:1)</td>
                <td>비밀글가능 (Y,N)</td>
            </tr>
        </table>
    </td>
</tr>


<tr class="table-danger">
    <th>Board</th>
    <td>data</td>
    <td>게시물 정보</td>
    <td>예) <a href="<?=SITE?>/Board/data?post_no" target="_blank"><?=SITE?>/Board/data?post_no=</a></td>
</tr>
<tr>
    <td>GET</td>
    <td class="table-info align-middle justify-content-end" align="right">요청</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>post_no</td>
                <td>String</td>
                <td>게시물번호</td>
            </tr>
        </table>
    </td>
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
                <td>배열데이터(row,board_info,permission)</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td>JSON</td>
    <td class="table-info align-middle justify-content-end" align="right">row</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>post_no</td>
                <td>String</td>
                <td>성공여부(TRUE,FALSE)</td>
            </tr>
            <tr>
                <td>bcode</td>
                <td>String</td>
                <td>게시판 코드</td>
            </tr>
            <tr>
                <td>user_id</td>
                <td>String</td>
                <td>등록한 회원id</td>
            </tr>
            <tr>
                <td>name</td>
                <td>String</td>
                <td>등록자이름(비회원)</td>
            </tr>
            <tr>
                <td>secret</td>
                <td>String</td>
                <td>비밀글여부(T:비밀글,F:일반글)  </td>
            </tr>
            <tr>
                <td>tel</td>
                <td>String</td>
                <td>연락처</td>
            </tr>
            <tr>
                <td>status</td>
                <td>String</td>
                <td>게시물상태(1: 노출,0:차단)</td>
            </tr>
            <tr>
                <td>subject</td>
                <td>String</td>
                <td>제목</td>
            </tr>
            <tr>
                <td>content</td>
                <td>String</td>
                <td>내용</td>
            </tr>
            <tr>
                <td>file</td>
                <td>String</td>
                <td>첨부파일</td>
            </tr>
            <tr>
                <td>write_date</td>
                <td>String</td>
                <td>등록일</td>
            </tr>
            <tr>
                <td>modify_date</td>
                <td>String</td>
                <td>수정일</td>
            </tr>
            <tr>
                <td>add01</td>
                <td>String</td>
                <td>추가1</td>
            </tr>
            <tr>
                <td>add02</td>
                <td>String</td>
                <td>추가2</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td>JSON</td>
    <td class="table-info align-middle justify-content-end" align="right">board_info</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>header_text</td>
                <td>String</td>
                <td>상단텍스트</td>
            </tr>
            <tr>
                <td>header_image</td>
                <td>String</td>
                <td>상단이미지</td>
            </tr>
            <tr>
                <td>footer_text</td>
                <td>String</td>
                <td>하단텍스트</td>
            </tr>
            <tr>
                <td>footer_image</td>
                <td>String</td>
                <td>하단이미지</td>
            </tr>
            <tr>
                <td>name</td>
                <td>String</td>
                <td>게시판명</td>
            </tr>
            <tr>
                <td>perpage</td>
                <td>String</td>
                <td>페이지당 게시물수</td>
            </tr>
            <tr>
                <td>style</td>
                <td>String</td>
                <td>적용스타일  </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td>JSON</td>
    <td class="table-info align-middle justify-content-end" align="right">permission</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>write</td>
                <td>String</td>
                <td>쓰기권한(admin:관리자만, member: 회원만, every:누구나)</td>
            </tr>
            <tr>
                <td>read</td>
                <td>String</td>
                <td>읽기권한 (self: 자기글만 ,member: 회원만, every:누구나)</td>
            </tr>
            <tr>
                <td>list</td>
                <td>String</td>
                <td>리스트 보기 권한 (self: 자기글만, every:누구나)</td>
            </tr>
            <tr>
                <td>reply</td>
                <td>String(max:1)</td>
                <td>댓글기능가능 (Y,N)</td>
            </tr>
            <tr>
                <td>secret</td>
                <td>String(max:1)</td>
                <td>비밀글가능 (Y,N)</td>
            </tr>
        </table>
    </td>
</tr>

<tr class="table-danger">
    <th>Board</th>
    <td>list</td>
    <td>게시물 리스트</td>
    <td>예) <a href="<?=SITE?>/Board/list?page=1" target="_blank"><?=SITE?>/Board/list?page=1</a></td>
</tr>
<tr>
    <td>GET</td>
    <td class="table-info align-middle justify-content-end" align="right">요청</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>* code</td>
                <td>String</td>
                <td>게시판코드(필수)</td>
            </tr>
            <tr>
                <td>* page</td>
                <td>String</td>
                <td>페이지 번호(default:1)</td>
            </tr>
            <tr>
                <td>limit</td>
                <td>String</td>
                <td>가져올 게시물수(default:10,생략가능)</td>
            </tr>
        </table>
    </td>
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
                <td>배열데이터(rows,pager_links,board_info,permission)</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td>JSON</td>
    <td class="table-info align-middle justify-content-end" align="right">rows</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>post_no</td>
                <td>String</td>
                <td>성공여부(TRUE,FALSE)</td>
            </tr>
            <tr>
                <td>bcode</td>
                <td>String</td>
                <td>게시판 코드</td>
            </tr>
            <tr>
                <td>user_id</td>
                <td>String</td>
                <td>등록한 회원id</td>
            </tr>
            <tr>
                <td>name</td>
                <td>String</td>
                <td>등록자이름(비회원)</td>
            </tr>
            <tr>
                <td>secret</td>
                <td>String</td>
                <td>비밀글여부(T:비밀글,F:일반글)  </td>
            </tr>
            <tr>
                <td>tel</td>
                <td>String</td>
                <td>연락처</td>
            </tr>
            <tr>
                <td>status</td>
                <td>String</td>
                <td>게시물상태(1: 노출,0:차단)</td>
            </tr>
            <tr>
                <td>subject</td>
                <td>String</td>
                <td>제목</td>
            </tr>
            <tr>
                <td>content</td>
                <td>String</td>
                <td>내용</td>
            </tr>
            <tr>
                <td>file</td>
                <td>String</td>
                <td>첨부파일</td>
            </tr>
            <tr>
                <td>write_date</td>
                <td>String</td>
                <td>등록일</td>
            </tr>
            <tr>
                <td>modify_date</td>
                <td>String</td>
                <td>수정일</td>
            </tr>
            <tr>
                <td>add01</td>
                <td>String</td>
                <td>추가1</td>
            </tr>
            <tr>
                <td>add02</td>
                <td>String</td>
                <td>추가2</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td>JSON</td>
    <td class="table-info align-middle justify-content-end" align="right">pager_links</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>item_num_start</td>
                <td>String</td>
                <td>게시물의 시작번호</td>
            </tr>
            <tr>
                <td>now_page</td>
                <td>String</td>
                <td>현페이지</td>
            </tr>
            <tr>
                <td>total_items</td>
                <td>String</td>
                <td>총 게시물수</td>
            </tr>
            <tr>
                <td>total_page</td>
                <td>String</td>
                <td>총페이지수</td>
            </tr>
            <tr>
                <td>page_per_items</td>
                <td>String</td>
                <td>페이지당게시물수</td>
            </tr>
            <tr>
                <td>block_start</td>
                <td>String</td>
                <td>블럭 시작번호</td>
            </tr>
            <tr>
                <td>block_end</td>
                <td>String</td>
                <td>블럭 끝번호</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td>JSON</td>
    <td class="table-info align-middle justify-content-end" align="right">board_info</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>header_text</td>
                <td>String</td>
                <td>상단텍스트</td>
            </tr>
            <tr>
                <td>header_image</td>
                <td>String</td>
                <td>상단이미지</td>
            </tr>
            <tr>
                <td>footer_text</td>
                <td>String</td>
                <td>하단텍스트</td>
            </tr>
            <tr>
                <td>footer_image</td>
                <td>String</td>
                <td>하단이미지</td>
            </tr>
            <tr>
                <td>name</td>
                <td>String</td>
                <td>게시판명</td>
            </tr>
            <tr>
                <td>perpage</td>
                <td>String</td>
                <td>페이지당 게시물수</td>
            </tr>
            <tr>
                <td>style</td>
                <td>String</td>
                <td>적용스타일  </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td>JSON</td>
    <td class="table-info align-middle justify-content-end" align="right">permission</td>
    <td class="table-info" colspan="2">
        <table class="table table-dark table-striped">
            <tr>
                <th>이름</th>
                <th>형태</th>
                <th>설명</th>
            </tr>
            <tr>
                <td>write</td>
                <td>String</td>
                <td>쓰기권한(admin:관리자만, member: 회원만, every:누구나)</td>
            </tr>
            <tr>
                <td>read</td>
                <td>String</td>
                <td>읽기권한 (self: 자기글만 ,member: 회원만, every:누구나)</td>
            </tr>
            <tr>
                <td>list</td>
                <td>String</td>
                <td>리스트 보기 권한 (self: 자기글만, every:누구나)</td>
            </tr>
            <tr>
                <td>reply</td>
                <td>String(max:1)</td>
                <td>댓글기능가능 (Y,N)</td>
            </tr>
            <tr>
                <td>secret</td>
                <td>String(max:1)</td>
                <td>비밀글가능 (Y,N)</td>
            </tr>
        </table>
    </td>
</tr>