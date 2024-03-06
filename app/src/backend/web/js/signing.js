/**
 * Функция возвращает true, если браузер совместим с механизмомо подписания на 
 * клиенте, иначе возвращает false.
 * @returns {Boolean}
 */
function isCompatibleBrowser()
{
    try {
        if (typeof CyberFT !== undefined) { 
                return CyberFT.isReady(); 
        }
    } catch(e) {
        console.debug(e.message);
    }

    return false;
}

//function signingMessage(msg)
//{
//	var myMsg = $("#Message");
//	msg = '<div class="col-sm-12"><p>' + msg + '</p></div>';
//	myMsg.html(msg);
//}