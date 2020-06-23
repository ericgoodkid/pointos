$(document).ready(function () {
    const getConstant = function() {
        return {
            oTxtUserName : $('#txtUsername'),
            oTxtPassword : $('#txtPassword'),
            oBtnLogin    : $('#btnLogin')
        };

    }();

    const oManage = function() {
        const init = async function() {
            initActions();
        }

        const initActions = function() {
            getConstant.oBtnLogin.click(async function() {
                const sTempButton = $(this).html();
                $(this).html( `<i class="fa fa-spinner fa-spin"></i>`);
                await clickLogin();
                $(this).html(sTempButton);
            })
            
        }

        const clickLogin = async function() {
            await login();
        }

        const login = async function() {
            const oItem = getForm();
            if (oItem['username'].length === 0) {
                loginResult({
                    'bResult' : false,
                    'sType'    : 'noUsername'
                });
                return;
            }            
            
            if (oItem['password'].length === 0) {
                loginResult({
                    'bResult' : false,
                    'sType'    : 'noPassword'
                });
                return;
            }

            const oResult = await axios.post('/api/front/login', oItem).then(oResponse => oResponse.data);
            loginResult(oResult);
        }

        const loginResult = function(oParams) {
            const {bResult, sType} = oParams;
            if (bResult === true) {
                window.location = "api/front/redirect";
                return;
            }

            if (sType === 'noUsername') {
                toastr.error(`Please enter username`);
                return;
            }

            if (sType === 'noPassword') {
                toastr.error(`Please enter password`);
                return;
            }

            if (sType === 'username') {
                toastr.error(`Your username doesn't match any in our records`);
                return;
            }
            
            if (sType === 'password') {
                toastr.error(`Your password do not match`);
                return;
            }
            
            toastr.error(`Error occured`);
        }

        const getForm = function() {
            const sUsername =  getConstant.oTxtUserName.val();
            const sPassword =  getConstant.oTxtPassword.val();
            return {
                username : sUsername,
                password : sPassword
            }
        }


        return {
            init
        }
    }();

    oManage.init();
});