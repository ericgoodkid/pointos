$(document).ready(function () {
    const getConstant = function() {
        return {
            oTable            : $('#tblItems'),
            oTxtName          : $('#txtName'),
            oBtnSave          : $('#btnSave'),
            oLblAction        : $('#lblAction'),
            oFormItem         : $('#formItem'),
            oModalOrder            : $('#modal-order'),
            oModalEdit            : $('#modal-edit'),
            oTblPreview            : $('#tblPreview'),
            oTblEdit            : $('#tblEdit'),
            oBtnAdd           : $('#btnAdd'),
            oLblCode           : $('#lblCode'),
            oBtnSaveEdit           : $('#btnSaveEdit'),
            oTxtPayCash           : $('#txtPayCash'),
        };

    }();

    const oManage = function() {
        let oTable = null;
        let sCurrentCode = null;
        let aCode = [];
        let aDiscount = [];

        const init = async function() {
            oTable = await initTable();
            aDiscount = await getLayoutConstant.getDiscountList();
            initActions();
        }

        const initActions = function() {
            getConstant.oBtnSave.click(async function() {
                const sTempButton = $(this).html();
                $(this).html(getLayoutConstant.sLoadingIcon);
                await clickSave();
                oTable.ajax.reload( null, false );
                $(this).html(sTempButton);
            })

            getConstant.oBtnAdd.click(async function() {
                getConstant.oLblAction.html('Add ');
                getConstant.oFormItem.trigger('reset');
            })

            $('.dataTable').on('click', 'a.aDelete',async function(){
                await deleteItem(this);
            });

            $('.dataTable').on('click', 'a.aEdit',async function(){
                sCurrentCode = $(this).closest('td').next().html();
                await clickEdit(this);
                fillTotalFieldsEdit();
                addDiscountPreview();
            });

            $('.dataTable').on('click', 'a.aPreview',async function(){
                await clickPreview(this);
            });

            $('#tblEdit').on('click', '.btnRemoveItem',async function(){
                await clickRemoveItem(this);
                fillTotalFieldsEdit();
                addDiscountPreview();
            });
            
            $('#tblEdit').on('input', 'input.txtQuantity',async function(){
                await changeQuantity(this);
                fillTotalFieldsEdit();
                addDiscountPreview();
            });

            $('#tblEdit').on('focusout', 'input.txtQuantity',async function(){
                await focusoutQuantity(this);
                fillTotalFieldsEdit();
                addDiscountPreview();
            });
            
            getConstant.oBtnSaveEdit.click(async function() {
                const sTempButton = $(this).html();
                $(this).html(getLayoutConstant.sLoadingIcon);
                await clickSaveEdit();
                $(this).html(sTempButton);
            })
        }

        const clickSaveEdit = async function (){
            const aOrderItem = getFormWithDiscount();
                const oItem = {
                    'sCash' : getConstant.oTxtPayCash.val(),
                    'aOrderItem' : aOrderItem
                };
            const oResult = await axios.put(`/api/admin/order/${sCurrentCode}`, {
                oItem
            }).then(oResponse => oResponse.data);
            const aTempResult = {
                bResult : oResult.bResult,
                aMessage : oResult.aMessage[0]
            };

            await oTable.ajax.reload( null, false );

            getLayoutConstant.promptMessage(aTempResult);
            if (oResult.bResult === false) {
                return;
            }

            getConstant.oModalEdit.modal('hide')
        }

        const clickRemoveItem = async function(oEvent) {
            const oTr = $(oEvent).closest('tr');
            const sCode = $(oEvent).data('code');
            removeInList(oTr, sCode);
        }

        const removeInList = function(oTr, sCode) {
            oTr.remove();
            aCode.splice(aCode.indexOf(sCode), 1);
        }

        const clickPreview = async function(oEvent) {
            const sCode = $(oEvent).closest('td').next().html();
            const oResult = await axios.get(`/api/admin/order/${sCode}`).then(oResponse => oResponse.data);
            if (oResult.bResult === false) {
                getLayoutConstant.promptMessage(oResult);
                return;
            }

            fillOrderPreview(oResult.oItem.get_order_item);
            fillTotalFields(oResult.oItem.get_order_item);
            getConstant.oLblCode.html(sCode);
            getConstant.oModalOrder.modal('show')
        }

        const fillOrderPreview = function(aOrder) {
            getConstant.oTblPreview.find('tbody').html('');
           
            for (const oOrder of aOrder) {
                let sDescription = `<h5>${oOrder.product_name} <span style="font-style:italic;font-size:16px;">(${oOrder.product_brand})</span></h5>`;
                const sPrice = `<h5> ₱ ${getLayoutConstant.formatMoney(parseFloat(oOrder.product_price))}</h5>`;
                const sQuantity = `<h5> ${oOrder.quantity}</h5>`;
                let iAmount = parseFloat(oOrder.product_price);
                let sAmount = `<h5> ₱ ${getLayoutConstant.formatMoney(iAmount)}</h5>`;
                if (oOrder.discount_name !== null) {
                    const iPercentage = parseInt(oOrder.discount_percentage, 10) / 100;
                    const iDiscountedPrice = parseFloat(oOrder.product_price) - (parseFloat(oOrder.product_price) * (iPercentage));
                    iAmount = iDiscountedPrice;
                    sAmount = `<h5> ₱ ${getLayoutConstant.formatMoney(iDiscountedPrice)} (${oOrder.discount_percentage}%)</h5> <span style="font-style:italic;font-size:15px">${oOrder.discount_name}</span>`;
                }
                const iSubTotal = iAmount * parseInt(oOrder.quantity, 10);
                let sSubTotal = `<h5> ₱ ${getLayoutConstant.formatMoney(iSubTotal)}</span></h5>`;

                const oTr = `
                    <tr>
                        <td>${sDescription}</td>
                        <td>${sPrice}</td>
                        <td>${sAmount}</td>
                        <td>${sQuantity}</td>
                        <td>${sSubTotal}</td>
                    </tr>
                `;
                
                getConstant.oTblPreview.find('tbody').append(oTr);
            }
        };

        const computeTotals = function(aOrder) {
            let iTotalAmount = 0;
            let iTotalDiscountedAmount = 0;
            let iTotalSubTotalAmount = 0;
            let iTotalQuantity = 0;

            for (const oOrder of aOrder) {
                const iPercentage = oOrder.discount_name === null ? 0 : parseInt(oOrder.discount_percentage, 10) / 100;
                const iDiscountedPrice = parseFloat(oOrder.product_price) - (parseFloat(oOrder.product_price) * (iPercentage));
                iTotalDiscountedAmount += iDiscountedPrice;
                iTotalSubTotalAmount += iDiscountedPrice * parseInt(oOrder.quantity, 10);
                iTotalAmount += parseFloat(oOrder.product_price);
                iTotalQuantity += parseInt(oOrder.quantity, 10);
            }

            return {
                iTotalAmount,
                iTotalSubTotalAmount,
                iTotalDiscountedAmount,
                iTotalQuantity
            }
        }

        const getForm = function() {
            let aOrder = [];
            $('.txtQuantity').each(function() {
                const iQuantity = parseInt($(this).val(), 10);
                const sProductCode = $(this).data('code');

                aOrder.push({
                    sProductCode,
                    iQuantity
                })
            });

            return aOrder;
        }

        const computeTotalsEdit = function() {
            let iTotalAmount = 0;
            let iTotalDiscountedAmount = 0;
            let iTotalQuantity = 0;

            $('.txtQuantity').each(function() {
                const iQuantity = parseInt($(this).val(), 10);
                let iDiscountedPrice = parseFloat($(this).data('discounted-price'));
                const iPrice = parseFloat($(this).data('price'));
                const sCode = $(this).data('code');
                const oDiscount = getProductDiscount(sCode);

                if (oDiscount !== false) {
                    const iPercentage = oDiscount.amount / 100;
                    iDiscountedPrice = iPrice - (iPrice * (iPercentage));
                }

                iTotalQuantity += iQuantity;
                iTotalDiscountedAmount += iDiscountedPrice * iQuantity;
                iTotalAmount += iPrice * iQuantity;
            });

            iTotalDiscountedAmount = iTotalDiscountedAmount.toFixed(2);
            iTotalAmount = iTotalAmount.toFixed(2);

            return {
                iTotalAmount,
                iTotalDiscountedAmount,
                iTotalQuantity
            }
        }


        const getProductDiscount = function(sProductCode, iQuantity) {
            let aFinalPromo = [];
            let aPromo = aDiscount.filter(aDiscount => {
                let oDiscount = aDiscount.get_discount_item.filter(oProduct => {
                    return oProduct.get_product.code == sProductCode
                }) 

                return oDiscount.length != 0;
            });

            if (aPromo.length === 0) {
                return false;
            }

            const aOrderItem = getForm();
            for (const oPromo of aPromo) {
                const aProductCode = getProductCodeList(oPromo);
                let iTotalQuantity = 0;
                for (const oOrderItem of aOrderItem) {
                    if (aProductCode.includes(oOrderItem.sProductCode) === false) {
                        continue;
                    }

                    iTotalQuantity += oOrderItem.iQuantity;
                }

                if (oPromo.minimum > iTotalQuantity) {
                    continue;
                }

                aFinalPromo.push(oPromo);
            }

            if (aFinalPromo.length === 0) {
                return false;
            }

            aFinalPromo = aFinalPromo.sort((oFirstItem, oSecondItem) => {
                return oSecondItem.amount - oFirstItem.amount;
            })
            
            return aFinalPromo[0];
        }

        const getProductCodeList = function(aPromo) {
            const aCode = [];
            for (const oPromo of aPromo.get_discount_item) {
                aCode.push(oPromo.get_product.code)
            }

            return aCode;
        }


        const fillTotalFields = function(aOrder = null) {
            const {iTotalAmount, iTotalSubTotalAmount, iTotalDiscountedAmount, iTotalQuantity} = computeTotals(aOrder);
            console.log([iTotalAmount, iTotalSubTotalAmount, iTotalDiscountedAmount, iTotalQuantity])
            const oTblTotalItem = aOrder === null ? getConstant.oTblEdit : getConstant.oTblPreview;
            const oTdTotalAmount = oTblTotalItem.find('tfoot').find('th:eq(2)');
            const oTdTotalCost = oTblTotalItem.find('tfoot').find('th:eq(1)');
            const oTdTotalDiscountedAmount = oTblTotalItem.find('tfoot').find('th:eq(4)');
            const oTdTotalQuantity = oTblTotalItem.find('tfoot').find('th:eq(3)');

            oTdTotalAmount.html(`₱ ${getLayoutConstant.formatMoney(parseFloat(iTotalDiscountedAmount))}`);
            oTdTotalCost.html(`₱ ${getLayoutConstant.formatMoney(parseFloat(iTotalAmount))}`);
            oTdTotalDiscountedAmount.html(`₱ ${getLayoutConstant.formatMoney(parseFloat(iTotalSubTotalAmount))}`);
            oTdTotalQuantity.html(`${iTotalQuantity}`);
        }

        const fillTotalFieldsEdit = function() {
            const {iTotalAmount, iTotalDiscountedAmount, iTotalQuantity} = computeTotalsEdit();
            const oTblTotalItem = getConstant.oTblEdit;
            const oTdTotalAmount = oTblTotalItem.find('tfoot').find('th:eq(2)');
            const oTdTotalCost = oTblTotalItem.find('tfoot').find('th:eq(1)');
            const oTdTotalDiscountedAmount = oTblTotalItem.find('tfoot').find('th:eq(4)');
            const oTdTotalQuantity = oTblTotalItem.find('tfoot').find('th:eq(3)');

            oTdTotalAmount.html(`₱ ${getLayoutConstant.formatMoney(parseFloat(iTotalDiscountedAmount))}`);
            oTdTotalCost.html(`₱ ${getLayoutConstant.formatMoney(parseFloat(iTotalAmount))}`);
            oTdTotalDiscountedAmount.html(`₱ ${getLayoutConstant.formatMoney(parseFloat(iTotalDiscountedAmount))}`);
            oTdTotalQuantity.html(`${isNaN(iTotalQuantity) === true ? 0 : iTotalQuantity}`);
        }

        const clickSave = async function() {
            if (getConstant.oLblAction.html().trim() === 'Add') {
                await createItem();
                return;                
            }

            await updateItem();
        }

        const updateItem = async function() {
            const oItem = getForm();
            const oResult = await axios.put('/api/admin/brand', {
                oItem
            }).then(oResponse => oResponse.data);

            getLayoutConstant.promptMessage(oResult);
            if (oResult.bResult === false) {
                return;
            }

            getConstant.oModal.modal('hide');
            getConstant.oFormItem.trigger('reset');
        }

        const getItem = async function(sCode) {
            return await axios.get(`/api/admin/brand/${sCode}`)
            .then(oResponse => oResponse.data);
        }

        const clickEdit = async function(oEvent) {
            const sCode = $(oEvent).closest('td').next().html();
            const oResult = await axios.get(`/api/admin/order/${sCode}`).then(oResponse => oResponse.data);
            if (oResult.bResult === false) {
                getLayoutConstant.promptMessage(oResult);
                return;
            }

            const oItem = oResult.oItem;
            getConstant.oModalEdit.modal('show');
            getConstant.oModalEdit.find('tbody').html('');
            getConstant.oTxtPayCash.val(parseFloat(oItem.cash));

            for (const oOrder of oItem.get_order_item) {
                const oTr = generateTr(oOrder);
                getConstant.oModalEdit.find('tbody').append(oTr);
            }
            
        }

        const generateTr = function(oItem) {
            const sName = oItem.product_name;
            const sCode = oItem.get_product.code;
            const iPrice = parseFloat(oItem.product_price);
            const iMax = parseInt(oItem.quantity, 10);
            const sBrand = oItem.product_brand;
            return `
                <tr>
                    <td>
                        <button type="button" class="close mt-3 btnRemoveItem" data-code="${sCode}">
                        ×
                        </button>
                    </td>
                    <td>
                        <h5>${sName} <span style="font-style:italic;font-size:16px;">(${sBrand})</span></h5> 
                        <span style="font-weight:bold;">(${iMax} item available)</span>
                    </td>
                    <td>
                        <h5>₱ ${getLayoutConstant.formatMoney(iPrice)}</h5>
                    </td>
                    <td>
                        <h5>₱ ${getLayoutConstant.formatMoney(iPrice)}</h5>
                    </td>
                    <td>
                        <input type="number" class="form-control txtQuantity text-center" data-max="${iMax}" data-price="${iPrice}" data-discounted-price="${iPrice}" data-code="${sCode}" min="0" max="${iMax}" value="${iMax}">
                    </td>
                    <td>
                        <h5>₱ ${getLayoutConstant.formatMoney(iPrice)}</h5>
                    </td>
                </tr>
            `;
        };

        const changeQuantity = async function(oEvent) {
            let iQuantity = $(oEvent).val() === '' ? $(oEvent).val() : parseInt($(oEvent).val(), 10);
            const iMax = $(oEvent).data('max');
            const iDiscountedPrice = $(oEvent).data('discounted-price');
            const sCode = $(oEvent).data('code');
            const oTr = $(oEvent).closest('tr');
            if (iQuantity === 0) {
                removeInList(oTr, sCode);
                return;
            }

            if (iQuantity > iMax) {
                $(oEvent).val(iMax); 
                iQuantity = iMax;
            }

            const oSubTotalTd = $(oEvent).closest('tr').find('td:eq(5)');
            const oAmountTd = $(oEvent).closest('tr').find('td:eq(3)');
            changeSubTotal(oSubTotalTd, iQuantity, iDiscountedPrice);
            changeSubTotal(oAmountTd, 1, iDiscountedPrice);

        };

        const focusoutQuantity = async function(oEvent) {
            let iQuantity = $(oEvent).val();
            const oTr = $(oEvent).closest('tr');
            const sCode = $(oEvent).data('code');

            if (iQuantity === '') {
                removeInList(oTr, sCode);
            }

        }

        const addDiscountPreview = function() {
            $('.txtQuantity').each(function() {
                const iQuantity = parseInt($(this).val(), 10);
                const iPrice = parseFloat($(this).data('price'));
                const sCode = $(this).data('code');
                const oDiscount = getProductDiscount(sCode);
                if (oDiscount === false) {
                    const iTotalDiscountedPrice = iPrice * iQuantity;
                    const oTdAmount = $(this).closest('tr').find('td:eq(3)');
                    const oTdTotalAmount = $(this).closest('tr').find('td:eq(5)');
                    const sTdAmount = `<h5>₱ ${ iPrice.toFixed(2) } <span style="font-style:italic"></span></h5> `;
                    const sTdTotalAmount = `<h5>₱ ${ iTotalDiscountedPrice.toFixed(2) }</h5>`;
                    oTdAmount.html(sTdAmount);
                    oTdTotalAmount.html(sTdTotalAmount);
                    return;
                }


                const iPromoPercentage = oDiscount.amount;
                const iPercentage = oDiscount.amount / 100;
                const iDiscountedPrice = iPrice - (iPrice * (iPercentage));
                const iTotalDiscountedPrice = iDiscountedPrice * iQuantity;
                const oTdAmount = $(this).closest('tr').find('td:eq(3)');
                const oTdTotalAmount = $(this).closest('tr').find('td:eq(5)');
                const sTdAmount = `<h5>₱ ${ iDiscountedPrice.toFixed(2) } <span style="font-style:italic">(${iPromoPercentage}%)</span></h5> `;
                const sTdTotalAmount = `<h5>₱ ${ iTotalDiscountedPrice.toFixed(2) }</h5>`;
                oTdAmount.html(sTdAmount);
                oTdTotalAmount.html(sTdTotalAmount);
            });
        };

        const getFormWithDiscount = function() {
            let aOrder = [];
            const aItem = getForm();
            for (const oItem of aItem) {
                const sProductCode = oItem.sProductCode;
                const iQuantity = oItem.iQuantity;
                const mDiscountCode = getProductDiscount(sProductCode);
                const mFinalDiscountCode = mDiscountCode === false ? null : mDiscountCode.code;
                
                aOrder.push({
                    sProductCode,
                    sDiscountCode : mFinalDiscountCode,
                    iQuantity
                })
            }

            return aOrder;
        }

        const changeSubTotal = function(oTd, iQuantity, iPrice) {
            const iSubTotalPrice = (iPrice * iQuantity).toFixed(2);
            const sTd = `<h5>₱ ${ iSubTotalPrice }</h5>`;
            oTd.html(sTd);
        };

        const updateModal = function(oItem) {
            const {name} = oItem
            getConstant.oTxtName.val(name);
        }

        const deleteItem = async function(oEvent) {
            const sCode = $(oEvent).closest('td').next().html();
            const bConfirm = await Swal.fire({
                title: `Are you sure to delete this?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            });
            if (typeof bConfirm.dismiss === 'string') {
                return;
            }

            const oResponse = await axios.delete(`/api/admin/order/${sCode}`);
                        
            getLayoutConstant.promptMessage(oResponse.data)
            oTable.ajax.reload( null, false );
        }

        const initTable = function() {
            return getConstant.oTable.DataTable({
                'ajax': {
                    'url'  : '/api/admin/order',
                    'type' : 'get'
                },
                order: [[1, 'desc']]
            });
        }

        return {
            init
        }
    }();

    oManage.init();
});