/***
 * 每次请求接口时，进行登录状态检查，如果状态码为4001，返回到登录界面
 * @param result
 */
function loginStatusCheck(result)
{
  // console.log(result);
  if (result.code === 4001) {
    top.location.href = '../Login/login.html';
    $.cookie('admin', null);
  }
}

