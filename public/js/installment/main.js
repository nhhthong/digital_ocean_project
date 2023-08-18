$(document).ready(function() {
  
    const manageAlert = (
      () => {
        const message =  document.querySelector('.loading-read-file');
        const loading = `<div class="loading-progress-bar">
                          <div class="bar"> &nbsp;  &nbsp; Đang đọc dữ liệu từ file</div>
                         </div>`;

        return {
          // Hàm gọi manageAlert
          call() {
            Swal.fire({
                title: `<h4>
                          <a href="/installment/download-template-excel" class="template"><i class="fa fa-download"></i>&nbsp;Tải file template </a>
                        </h4>`,
                html : ` <form action="#" method="POST" id="form-file" enctype="multipart/form-data">
                            <input type="file" name="export_excel" accept=".xlsx,.xls,.csv"  value="Import File">
                        </form>`,
                showCancelButton: true,
                confirmButtonText: 'Import',
                denyButtonText: `Cancel`,
              }).then((result) => {
        
                if (result.isConfirmed) {
                    const file = document.querySelector('#form-file');
                    if(file[0].files[0]) {

                        message.innerHTML = loading;
                        file.submit();
                       
                    }else {
                      // Gọi lại nếu user chưa chọn file
                      this.call();
                    }
                }
              });
          }
        }
    }) ();

    const btnImport = $('#import-file');
    btnImport.click(function() {
      manageAlert.call();
      const fileName = document.querySelector('#form-file');
    });


    // chứa các method và property liên quan tới table báo cáo
    const manager = (
      () => {

        const dateStart = $('input[name="date-start"]')[0];
        const dateEnd   = $('input[name="date-end"]')[0];
        const url ='/installment/api-getdata-sellout';
        const method = 'POST';
        const rangeMonth = 3;
        const loading = `<div class="loading-report-table"></div>`;
        const digit = 2;
        const titleTotal = 'Grand Total';
        return {
          // date :obj Date
          objDate(date) {
            return {
              day: date.getDate(),
              month: date.getMonth()+1,
              year : date.getFullYear(),
            };
          },

          // format date từ objDate
          dateToString(objDate) {
            const day   = String(objDate.day).padStart(2,'0');
            const month = String(objDate.month).padStart(2,'0');
            const year  = String(objDate.year);
            return {
              'Y-m-d' : `${year}-${month}-${day}`,
              'd/m/Y' : `${day}/${month}/${year}`,
            };
          },
          
          callAjax(url , method, data, callback) {
            $.ajax({
              type: method,
              url: url,
              data: data,
              dataType: "JSON",
              success: callback
            });
          },
          // hàm render table tổng cộng
          renderReportModel(dateStart, dateEnd) {
    
            const totalTable = $('#total-table')[0];          
            const data = {
              method: 'get-data-report',
              dateStart: dateStart,
              dateEnd: dateEnd
            };       
            // Hiện loading
            totalTable.innerHTML = `<tr>
                                      <td colspan="5">
                                      ${loading}
                                      </Td>
                                    </tr>`;
            this.callAjax(url, method, data, (res) => {
              this.renderDate(dateStart, dateEnd,'info-date-model');
              // Xóa dữ liệu trước đó
              totalTable.innerHTML = '';
              if(res.length > 0) {
                // trường hợp có data trả về
                res.forEach( element => {
                  const row = document.createElement('tr');

                  let ins = Number(element['ins']);
                  let total = Number(element['total']);
                  if(ins && total) {
                    var ins_total =   ( (ins/total) *100).toFixed(digit) +'%'; 
                  }else {
                    var ins_total = '-';
                  }
                  let prevIns = element['prev_ins'];
                  if(ins && prevIns) {
                    var ins_prevIns = ( ( ins/ prevIns) *100).toFixed(digit) +'%';
                  }else {
                    var ins_prevIns = '-';
                  }

                  const htmls = `
                    <td style="text-align: left;"> ${element['desc']} </td>
                    <td> ${element['total']} </td>
                    <td> ${element['ins'] || '-'} </td>
                    <td> ${ins_prevIns || '-' }</td>
                    <td> ${ins_total || '-' }</td>
                  `;

                  row.innerHTML =  htmls;
                  totalTable.appendChild(row);
                })
              }else {
                // trường hợp không có data trả về
                const row = document.createElement('tr');
                row.innerHTML =  `<td colspan="5" rowspan="2" class="text-center-columns center-vertital-coloumn"> 
                                    <div class="empty-data-report">
                                      Không có dữ liệu
                                    </div> 
                                  </td>`;
                totalTable.appendChild(row);
              }

            });
          },
          // hàm hiển thị date đang search
          renderDate(dateStart, dateEnd, classElement) {
        
            const infoDate = $('.'+classElement);
            dateStart   = this.objDate(new Date(dateStart) );
            dateEnd   = this.objDate(new Date(dateEnd) );

            dateStart = this.dateToString(dateStart)['d/m/Y'];
            dateEnd   = this.dateToString(dateEnd)['d/m/Y'];              
          
            Array.from(infoDate).forEach( (value) => {
              value.innerText = `${dateStart} - ${dateEnd}`;
            })   
          },
          // Hàm render mục các kênh
          renderReportChannel(dateStart, dateEnd) {
 
            const totalTable = $('#channel-table')[0];          
            const data = {
              method: 'get-report-channel',
              dateStart: dateStart,
              dateEnd: dateEnd
            };       
              // Hiện loading
              totalTable.innerHTML = `<tr>
                                        <td colspan="11">
                                          ${loading}
                                        </Td>
                                     </tr>`;

            this.callAjax(url, method, data, (res) => {
              this.renderDate(dateStart, dateEnd, 'info-date-channel');
               // Xóa dữ liệu trước đó
              totalTable.innerHTML = '';

              if( res.length > 1 ) {
                // trường hợp có data trả về
                const lastRowIndex = res.length-1;
                res.forEach( (element, i) => {
                  const row = document.createElement('tr');
          
                  const htmls = `
                    <td style="text-align: left";> ${element['desc'] || titleTotal} </td>
                    <td> ${element['ins_total'] || '-'}</td>
                    <td> ${Number(element['ins/total']).toFixed(digit) +'%' || '-'}</td>
                    <td> ${element['ins_tgdd'] || '-'}</td>
                    <td> ${Number(element['fpt/total']).toFixed(digit) +'%' || '-'}</td>
                    <td> ${element['ins_fpt'] || '-' }</td>
                    <td> ${Number(element['fpt/total']).toFixed(digit)  +'%' || '-'}</td>
                    <td> ${element['ins_viettel'] || '-'}</td>
                    <td> ${Number(element['viettel/total']).toFixed(digit)  +'%' || '-'}</td>
                    <td> ${element['ins_others'] || '-'}</td>
                    <td> ${Number(element['others/total']).toFixed(digit)  +'%' || '-'}</td>      
                  `;
                  row.innerHTML =  htmls;
                  if(!element['desc']) {
                    row.classList.add('total-items');
                  }   

                  totalTable.appendChild(row);
                })

              }else {
                // trường hợp không có data trả về
                const row = document.createElement('tr');
                row.innerHTML =  `<td colspan="11" rowspan="2" class="text-center-columns center-vertital-coloumn"> 
                                    <div class="empty-data-report">
                                      Không có dữ liệu
                                    </div> 
                                  </td>`;
                totalTable.appendChild(row);
              }

            });
          },
           // Hàm render mục đối tác
          renderReportFinancier(dateStart, dateEnd) {
            const totalTable = $('#financier-table')[0];          
            const data = {
              method: 'get-report-financier',
              dateStart: dateStart,
              dateEnd: dateEnd
            };       
            // Hiện loading
            totalTable.innerHTML = `<tr>
                                      <td colspan="10">
                                        ${loading}
                                      </Td>
                                    </tr>`;

            this.callAjax(url, method, data, (res) => {
              this.renderDate(dateStart, dateEnd, 'info-date-financier');
              // Xóa dữ liệu trước đó
              totalTable.innerHTML = '';
              if( res.length > 1 ) {
                // trường hợp có data trả về
                res.forEach( (element, i) => {
                  const row = document.createElement('tr');

                  const htmls = `
                      <td style="text-align: left";>${
                        element['desc'] || titleTotal
                      }</td>
                      <td>${element['total']}</td>
                      <td>${element['sl_home_credit']}</td>
                      <td>${Number(element['home_credit/total']).toFixed(digit)}%</td>
                      <td>${element['sl_fe_credit']}</td>
                      <td>${Number(element['fe_credit/total']).toFixed(digit)}%</td>
                      <td>${element['sl_hd_saigon']}</td>
                      <td>${Number(element['hd_saigon/total']).toFixed(digit)}%</td>
                      <td>${element['sl_mcredit']}</td>
                      <td>${Number(element['mcredit/total']).toFixed(digit)}%</td>
                  `;    
                  if(!element['desc']) {
                    row.classList.add('total-items');
                  }   

                  row.innerHTML = htmls;
                  totalTable.appendChild(row);
                })

              }else {
                // trường hợp không có data trả về
                const row = document.createElement('tr');
                row.innerHTML =  `<td colspan="10" rowspan="2" class="text-center-columns center-vertital-coloumn"> 
                                    <div class="empty-data-report">
                                      Không có dữ liệu
                                    </div> 
                                  </td>`;
                totalTable.appendChild(row);
              }
                

            });
          },
          
          // render ra các table
          render(dateStart, dateEnd) {
            this.renderReportModel(dateStart, dateEnd);
            this.renderReportChannel(dateStart, dateEnd);
            this.renderReportFinancier(dateStart, dateEnd);
            
           
          },
          // Hàm xử lý các sự kiện 
          handleEvent() {
            // Xử lý khi ipdate start thay đổi value
            dateStart.onchange = (e) => {
              let current = new Date();
              
              let date = new Date($(dateStart).val() );
              let min  = this.objDate(date);
              
              dateEnd.min = this.dateToString(min)['Y-m-d'];
              
              // lấy obj date từ input date start + rangMonth
              date.setMonth(date.getMonth() + rangeMonth);
              let max;
              if(date.getTime() < current.getTime()) {
                max = date;
              }else{

                max = current;
              }
              max = this.objDate(max);
              stringDate = this.dateToString(max)['Y-m-d'];
              dateEnd.max = stringDate; 
              
            }
            // Xử lý khi người dùng click search 
            const btnSearch = $('#btn-search')[0];
            btnSearch.onclick = (e) => {
                // check thỏa mãn yêu cầu của form
                if (dateStart.checkValidity() && dateEnd.checkValidity()) {
                  const valDateStart = $(dateStart).val();
                  const valDateEnd   = $(dateEnd).val();
                  this.render(valDateStart, valDateEnd);

                }else {
                    // hiện ra yêu cầu nhập vào giá trị
                    dateEnd.reportValidity();
                    dateStart.reportValidity();
                    
                }
            }
          },
          // Hàm khởi tạo khi chương trình thực thi 
          init() {
            const valDateStart = $(dateStart).val();
            const valDateEnd   = $(dateEnd).val();
            this.handleEvent();
            this.render(valDateStart, valDateEnd);
          }

        }
      }
    )();

    manager.init();
  
   

    
  });