/**
 * 将短网址复制到剪切板
 */
function copyUrl() {
    let Url = document.getElementById("gen_result_url");
    Url.select();// 选择对象
    document.execCommand("Copy"); // 执行浏览器复制命令
    alert("已复制好，可贴粘。");
}

/**
 * 域名缩短
 */
function shorten() {
    let url = document.getElementById("inputContent").value;
    if (checkUrl(url)) {
        sendAJAX(url);
    }
}

/**
 * 检查url是否符合规则
 * @param url
 * @returns {boolean}
 */
function checkUrl(url) {
    if (!url.length) {
        msgAlert('url不能为空!', true);
        return false;
    }
    let hasHttp = /^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/)\w+([-.]\w+)*\.\w+([-.]\w+)*/.test(url),
        notHasHttp = /^\w+([-.]\w+)*\.\w+([-.]\w+)*/.test(url);
    if (!hasHttp && !notHasHttp) {
        msgAlert('输入的url有误，请重新输入!', true);
        return false;
    } else {
        document.getElementById('input-wrap').classList.remove('has-error');
        return true;
    }
}

/**
 * 关闭短网址消息框
 */
function closeWrapper() {
    document.getElementById('result-wrap').style.display = 'none'
}

/**
 * error消息提示框框
 * @param msg
 * @param input
 */
function msgAlert(msg, input) {
    let tips = document.getElementById('error-tips');
    tips.style.display = "block";
    tips.innerHTML = msg;
    input && (document.getElementById('input-wrap').classList.add('has-error'));
    setTimeout(function () {
        tips.style.display = 'none';
    }, 3000)
}

/**
 * 向服务器发送请求
 * @param url
 */
function sendAJAX(url) {
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                response200(xhr);
            } else {
                responseError(xhr);
            }
        }
    };
    let request = {
        "url": url
    };
    xhr.open('POST', "api/", true);
    xhr.send(JSON.stringify(request));
}

/**
 * 请求返回200时的处理函数
 * @param xmlHttpRequest
 */
function response200(xmlHttpRequest) {
    try {
        let result = JSON.parse(xmlHttpRequest.responseText);
        if (result.success === 'true') {
            let resultWrap = document.getElementById('result-wrap');
            resultWrap.style.display = 'block';
            let preViewBtn = document.getElementById('preViewBtn'),
                genResultUrl = document.getElementById('gen_result_url');
            preViewBtn.setAttribute('href', result.content.url);
            genResultUrl.value = result.content.url
            // location.protocol + '//' + location.host + '/' +
        } else {
            msgAlert(result.msg)
        }
    } catch (e) {
        console.log(e);
        msgAlert('服务器错误,请联系管理员或稍后再试!')
    }


}

/**
 * 请求返回非200时的处理函数
 * @param xmlHttpRequest
 */
function responseError(xmlHttpRequest) {
    msgAlert('返回错误');
}


function main() {
    // 标题动画
    let el = document.getElementsByTagName('body')[0];
    el.className = 'on';
    document.getElementById('shorten').onclick = shorten;
    document.getElementById("copy").onclick = copyUrl;
}

main();