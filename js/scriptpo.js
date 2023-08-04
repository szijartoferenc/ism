function Script() {
    this.registerEvents = function() {
      document.addEventListener('click', function(e) {
        targetElement = e.target;
        classList = targetElement.classList;
  
        if (classList.contains('updatePoBtn')) {
          e.preventDefault();
  
          batchNumber = targetElement.dataset.id;
          batchNumberContainer = 'container-' + batchNumber;
          
  
          // Get all purchase order and record
          productList = document.querySelectorAll('#' + batchNumberContainer + ' .po_product');
          qtyOrderedList = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_ordered');
          qtyReceivedList = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_received');
          supplierList = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_supplier');
          statusList = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_status');
          rowIds = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_row_id');
          pIds = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_productid');
  
          let poListsArr = [];
          for (let i = 0; i < productList.length; i++) {
            poListsArr.push({
              name: productList[i].innerText,
              qtyOrdered: qtyOrderedList[i].innerText,
              qtyReceived: qtyReceivedList[i].innerText,
              supplier: supplierList[i].innerText,
              status: statusList[i].innerText,
              id: rowIds[i].value,
              pid: pIds[i].value
            });
          }
  
          // Store in HTML
          let poListHtml = `
            <table id="formTable_${batchNumber}">
              <thead>
                <tr>
                  <th>Termék</th>
                  <th>Megrendelt mennyiség</th>
                  <th>Megérkezett mennyiség</th>
                  <th>Szállított mennyiség</th>
                  <th>Beszállító</th>
                  <th>Státusz</th>
                </tr>
              </thead>
              <tbody>`;
  
          poListsArr.forEach((poList) => {
            poListHtml += `
              <tr>
                <td class="po_product alignLeft">${poList.name}</td>
                <td class="po_qty_ordered">${poList.qtyOrdered}</td>
                <td class="po_qty_received">${poList.qtyReceived}</td>
                <td class="po_qty_delivered"><input type="number" value="0"/></td>
                <td class="po_qty_supplier alignLeft">${poList.supplier}</td>
                <td>
                  <select class="po_qty_status">
                    <option value="pending" ${poList.status === 'pending' ? 'selected' : ''}>Függőben</option>
                    <option value="incomplete" ${poList.status === 'incomplete' ? 'selected' : ''}>Meghiúsult</option>
                    <option value="complete" ${poList.status === 'complete' ? 'selected' : ''}>Teljesített</option>
                  </select>
                  <input type="hidden" class="po_qty_row_id" value="${poList.id}">
                  <input type="hidden" class="po_qty_pid" value="${poList.pid}">
                </td>
              </tr>`;
          });
          poListHtml += '</tbody></table>';
  
          pName = targetElement.dataset.name;
  
          BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_PRIMARY,
            title: `Megrendelés feltöltése: Tételszám #: <b>${targetElement.dataset.id}</b>`,
            message: poListHtml,
            callback: function(toAdd) {
              if (toAdd) {
                formTableContainer = 'formTable_' + batchNumber;
               
                qtyReceivedList = document.querySelectorAll('#' + formTableContainer + ' .po_qty_received');
                qtyDeliveredList = document.querySelectorAll('#' + formTableContainer + ' .po_qty_delivered input');
                statusList = document.querySelectorAll('#' + formTableContainer + ' .po_qty_status');
                rowIds = document.querySelectorAll('#' + formTableContainer + ' .po_qty_row_id');
                qtyOrdered = document.querySelectorAll('#' + formTableContainer + ' .po_qty_ordered');
                pids = document.querySelectorAll('#' + formTableContainer + ' .po_qty_pid');
  
                let poListsArrForm = [];
                for (let j = 0; j < qtyDeliveredList.length; j++) {
                  poListsArrForm.push({
                    qtyReceive:qtyReceivedList[j].innerText,
                    qtyDelivered: qtyDeliveredList[j].value,
                    status: statusList[j].value,
                    id: rowIds[j].value,
                    qtyOrdered: qtyOrdered[j].innerText,
                    pid: pids[j].value
                  });
                }
                  
                $.ajax({
                  method: 'POST',
                  data: {
                    payload: poListsArrForm,
                  },
                  url: 'database/update-order.php',
                  dataType: 'json',
                  success: function (data) {
                    message = data.message;
  
                    BootstrapDialog.alert({
                      type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
                      message: message,
                      callback: function () {
                        if (data.success) location.reload();
                      },
                    });
                  },
                });
              }
            },
          });
        }

        //If deliveries button clicked
        if (classList.contains('addDeliveryHistory')){
          let id =targetElement.dataset.id;
          
          $.get('database/view-delivery-history.php', {id: id}, function(data){
            if (data.length) {
             
                  rows = '';
                  data.forEach((row, id) =>{
                    receivedDate = new Date(row['date_received']);
                    rows +='\
                        <tr>\
                           <td>'+ (id + 1) +'</td>\
                           <td>'+ receivedDate.toUTCString()+ ' ' +receivedDate.getUTCHours() + ':' + receivedDate.getUTCMinutes() +'</td>\
                           <td>'+ row['qty_received']+'</td>\
                        </tr>'; 
                  });
                 
                  deliveryHistoryHtml = '<table class="deliveryHistoryTable">\
                    <thead>\
                            <tr>\
                              <th>#</th>\
                              <th>Megérkezés dátuma</th>\
                              <th>Megérkezkett mennyiség</th>\
                           </tr>\
                    </thead>\
                  <tbody> '+ rows +'</tbody>\
                  </table>';

                  BootstrapDialog.show({
                    title: '<b>Szállítási előzmények</b>',
                    type: BootstrapDialog.TYPE_PRIMARY,
                    message: deliveryHistoryHtml
                  });
              
            }else{
              BootstrapDialog.alert({
                title: '</b>Szállítási előzmény</b>',
                type: BootstrapDialog.TYPE_INFO,
                message: 'Szállítási előzmény nem található!'
              });
            }

          }, 'json');
          
        }
      });
    };
  
    this.initialize = function () {
      this.registerEvents();
    };
  }
  
  let script = new Script();
  script.initialize();
  