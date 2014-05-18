// Declaring required variables
var digits = "0123456789";
// non-digit characters which are allowed in phone numbers
var phoneNumberDelimiters = "()- ";
// characters which are allowed in international phone numbers
// (a leading + is OK)
var validWorldPhoneChars = phoneNumberDelimiters + "+";
// Minimum no of digits in an international phone no.
var minDigitsInIPhoneNumber = 7;

window.currentField = '';

function insertTag(tag) {
    var cObject = eval('editor_' + window.currentField);
    cObject.replaceRange(tag, cObject.getCursor());
}

function insertAtCursor(myField, myValue) {
    //IE support
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    }
    //MOZILLA/NETSCAPE support
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
                + myValue
                + myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
}

function checkAccess(fCheck) {
    if (fCheck.value == '*') {
        for (i in document.forms['adminForm'][fCheck.name]) {
            if (document.forms['adminForm'][fCheck.name][i].id != 'ch_access_*') {
                if (fCheck.checked) {
                    document.forms['adminForm'][fCheck.name][i].disabled = true;
                } else {
                    document.forms['adminForm'][fCheck.name][i].disabled = false;
                }
            }
        }
    }
}

function isInteger(s) {
    var i;
    for (i = 0; i < s.length; i++) {
        var c = s.charAt(i);
        if (((c < "0") || (c > "9")))
            return false;
    }
    // All characters are numbers.
    return true;
}

function trim(s) {
    var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not a whitespace, append to returnString.
    for (i = 0; i < s.length; i++) {
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (c != " ")
            returnString += c;
    }
    return returnString;
}
function stripCharsInBag(s, bag) {
    var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++) {
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1)
            returnString += c;
    }
    return returnString;
}

function checkInternationalPhone(p_strPhone) {
    var bracket = 3
    p_strPhone = trim(p_strPhone)
    if (p_strPhone.indexOf("+") > 1)
        return false
    if (p_strPhone.indexOf("-") != -1)
        bracket = bracket + 1
    if (p_strPhone.indexOf("(") != -1 && p_strPhone.indexOf("(") > bracket)
        return false
    var brchr = p_strPhone.indexOf("(")
    if (p_strPhone.indexOf("(") != -1 && p_strPhone.charAt(brchr + 2) != ")")
        return false
    if (p_strPhone.indexOf("(") == -1 && p_strPhone.indexOf(")") != -1)
        return false
    s = stripCharsInBag(p_strPhone, validWorldPhoneChars);
    return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
}

function checkEmail(p_strEmail) {
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if (reg.test(p_strEmail) == false) {
        return false;
    } else {
        return true;
    }

}

function validateFFB(p_frmForm, p_bDebug, p_bAction, p_strAction) {
    var arrLabels = p_frmForm['labels'].value.split(';', 255);
    if (p_frmForm.messages != undefined) {
        var arrMesgs = p_frmForm['messages'].value.split(';', 255);
    } else {
        var arrMesgs = new Array(arrLabels.length);
        for (i = 0; i < arrLabels.length; i++) {
            arrMesgs[i] = '';
        }
    }
    var objMessagesShown = new Object;
    var objObjectsChecked = new Object;
    var iLabelIndex = 0;
    var strErrorMsg = '';

    for (i in p_frmForm) {
        try {
            fldName = String(p_frmForm[i].name != null ? p_frmForm[i].name : i);
        } catch (e) {
        }
        if (fldName.indexOf('fbform', 0) == 0 && !objObjectsChecked[fldName]) {
            if (p_frmForm[fldName].length > 0) {
                arrClasses = p_frmForm[fldName][0].className.split(' ', 255);
            } else {
                arrClasses = p_frmForm[fldName].className.split(' ', 255);
            }
            for (j in arrClasses) {
                if (arrClasses[j].toString() == 'required') {
                    if (p_frmForm[fldName].length > 0) {
                        strType = p_frmForm[fldName][0].type;
                    } else {
                        strType = p_frmForm[fldName].type;
                    }
                    switch (strType) {
                        case 'textarea':
                        case 'password':
                        case 'text':
                            if (trim(p_frmForm[fldName].value) == '') {
                                if (!objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' is required'))]) {
                                    strErrorMsg += (arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' is required')) + '<br/>';
                                    objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' is required'))] = true;
                                }
                            }
                            break;
                        case 'checkbox':
                            if (p_frmForm[fldName].length > 0) {
                                isChecked = false;
                                for (k in p_frmForm[fldName]) {
                                    if (p_frmForm[fldName][k].checked) {
                                        isChecked = true;
                                    }
                                }
                                if (!isChecked) {
                                    if (!objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ': at least one option must be selected'))]) {
                                        strErrorMsg += (arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ': at least one option must be selected')) + '<br/>';
                                        objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ': at least one option must be selected'))] = true;
                                    }
                                }
                            } else {
                                if (!p_frmForm[fldName].checked) {
                                    if (!objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' must be checked'))]) {
                                        strErrorMsg += (arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' must be checked')) + '<br/>';
                                        objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' must be checked'))] = true;
                                    }
                                }
                            }
                            break;
                        case 'radio':
                            if (p_frmForm[fldName].length > 0) {
                                isChecked = false;
                                for (k in p_frmForm[fldName]) {
                                    if (p_frmForm[fldName][k].checked) {
                                        isChecked = true;
                                    }
                                }
                                if (!isChecked) {
                                    if (!objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ': an option must be selected'))]) {
                                        strErrorMsg += (arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ': an option must be selected')) + '<br/>';
                                        objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ': an option must be selected'))] = true;
                                    }
                                }
                            } else {
                                if (!p_frmForm[fldName].checked) {
                                    if (!objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' must be checked'))]) {
                                        strErrorMsg += (arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' must be checked')) + '<br/>';
                                        objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' must be checked'))] = true;
                                    }
                                }
                            }
                            break;
                        case 'select':
                            if (p_frmForm[fldName].length > 0) {
                                isChecked = false;
                                for (k in p_frmForm[fldName]) {
                                    if (p_frmForm[fldName][k].checked) {
                                        isChecked = true;
                                    }
                                }
                                if (!isChecked) {
                                    if (!objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ': an option must be selected'))]) {
                                        strErrorMsg += (arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ': an option must be selected')) + '<br/>';
                                        objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ': an option must be selected'))] = true;
                                    }
                                }
                            } else {
                                if (!p_frmForm[fldName].checked) {
                                    if (!objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' must be checked'))]) {
                                        strErrorMsg += (arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' must be checked')) + '<br/>';
                                        objMessagesShown[(arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' must be checked'))] = true;
                                    }
                                }
                            }
                            break;
                    }
                }
                switch (arrClasses[j].toString()) {
                    case 'phone':
                        if (trim(p_frmForm[fldName].value) != '') {
                            if (!checkInternationalPhone(p_frmForm[fldName].value)) {
                                strErrorMsg += (arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' is not valid')) + '<br/>';
                            }
                        }
                        break;
                    case 'email':
                        if (trim(p_frmForm[fldName].value) != '') {
                            if (!checkEmail(p_frmForm[fldName].value)) {
                                strErrorMsg += (arrMesgs[iLabelIndex] != '' ? arrMesgs[iLabelIndex] : (arrLabels[iLabelIndex] + ' is not valid')) + '<br/>';
                            } else {
                                confField = document.getElementById(p_frmForm[fldName].name.replace("]", "_c]"));
                                if (confField != null && confField != undefined) {
                                    if (p_frmForm[fldName].value != confField.value) {
                                        custMsg = document.getElementById('match_' + p_frmForm[fldName].name.replace("]", "").replace("fbform[", "")).value;
                                        strErrorMsg += custMsg != '' ? custMsg : arrLabels[iLabelIndex] + ' is not confirmed correctly';
                                    }
                                }
                            }
                        }
                        break;
                    case 'password':
                        if (trim(p_frmForm[fldName].value) != '') {
                            confField = document.getElementById(p_frmForm[fldName].name.replace("]", "_c]"));

                            if (confField != null && confField != undefined) {
                                if (p_frmForm[fldName].value != confField.value) {
//                                    custMsg = document.getElementById('match_' + p_frmForm[fldName].name.replace("]", "").replace("fbform[", "")).value;
                                    strErrorMsg += arrLabels[iLabelIndex] + ' is not confirmed correctly';
                                }
                            }
                        }
                        break;
                }
            }
            iLabelIndex++;
            objObjectsChecked[fldName] = true;
        }
    }
    if (strErrorMsg != '') {
        showMsg(strErrorMsg);
        return false;
    }
    if (p_bDebug) {
        if (p_bAction) {
            self.location.href = p_strAction;
        } else {
            alert('Form is checked and valid');
        }
        return false;
    } else {
        return true;
    }
}

function closeIt() {
    document.getElementById('layerMask').style.display = 'none';
    document.getElementById('layerMaskBodyTNC').style.display = 'none';
    document.getElementById('layerMaskBodyPP').style.display = 'none';
    document.getElementById('layerMsgBody').style.display = 'none';
    document.getElementById('layerPreview').style.display = 'none';
    document.getElementById('previewFrame').src = "/templates/index.html";
    document.getElementById('layerImg').style.display = 'none';
    document.getElementById('imgFrame').src = "/templates/index.html";
}

function showImgTool(p_strURL, fId) {
    window.currentField = fId;
    document.getElementById('layerMask').style.display = 'block';
    document.getElementById('imgFrame').src = p_strURL;
    document.getElementById('layerImg').style.display = 'block';
}

function showPreview(p_strURL, submitForm, formId) {
    document.getElementById('layerMask').style.display = 'block';
    if (submitForm == true) {
        document.getElementById(formId).target = 'previewFrame';
        document.getElementById(formId).action = p_strURL;
        document.getElementById(formId).submit();
    } else {
        document.getElementById('previewFrame').src = p_strURL;
    }
    document.getElementById('layerPreview').style.display = 'block';
}

function showTNC() {
    FB.Canvas.scrollTo(0, 0);
    document.getElementById('layerMask').style.display = 'block';
    document.getElementById('layerMaskBodyTNC').style.display = 'block';
}

function showPP() {
    FB.Canvas.scrollTo(0, 0);
    document.getElementById('layerMask').style.display = 'block';
    document.getElementById('layerMaskBodyPP').style.display = 'block';
}

function showMsg(p_strMsgBody) {
    document.getElementById('layerMask').style.display = 'block';
    document.getElementById('msgTxt').innerHTML = p_strMsgBody;
    document.getElementById('layerMsgBody').style.display = 'block';
}