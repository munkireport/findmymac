<?php $this->view('partials/head', array('scripts' => array('clients/client_list.js'))); ?>

<div class="container-fluid">
    <div class="row pt-4">
        <div class="col-lg-12">
            <h3><span data-i18n="findmymac.listing.title"></span> <span id="total-count" class='badge badge-primary'>…</span></h3>
            <table class="table table-striped table-condensed table-bordered">
                <thead>
                    <tr>
                        <th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
                        <th data-i18n="serial" data-colname='machine.serial_number'></th>
                        <th data-i18n="username" data-colname='reportdata.long_username'></th>
                        <th data-i18n="findmymac.listing.status" data-colname='findmymac.status'></th>
                        <th data-i18n="findmymac.listing.ownerdisplayname" data-colname='findmymac.ownerdisplayname'></th>
                        <th data-i18n="findmymac.listing.email" data-colname='findmymac.email'></th>
                        <th data-i18n="findmymac.listing.add_time" data-colname='findmymac.add_time'></th>
                        <th data-i18n="findmymac.listing.personid" data-colname='findmymac.personid'></th>
                        <th data-i18n="findmymac.listing.hostname" data-colname='findmymac.hostname'></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-i18n="listing.loading" colspan="8" class="dataTables_empty"></td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- /span 12 -->
    </div> <!-- /row -->
</div>  <!-- /container -->

<script type="text/javascript">
	
	$(document).on('appUpdate', function(e){

	  var oTable = $('.table').DataTable();
	  oTable.ajax.reload();
	  return;

	});

	$(document).on('appReady', function(e, lang) {

		// Get column names from data attribute
		var columnDefs = [], //Column Definitions
            col = 0; // Column counter
		$('.table th').map(function(){
            columnDefs.push({name: $(this).data('colname'), targets: col, render: $.fn.dataTable.render.text()});
            col++;
		});
		var oTable = $('.table').dataTable( {
            ajax: {
                    url: appUrl + '/datatables/data',
                    type: "POST",
                    data: function(d){
                        d.mrColNotEmpty = "findmymac.status"

                        // status
                        if(d.search.value.match(/^status = \d$/))
                        {
                            // Add column specific search
                            d.columns[3].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
                            // Clear global search
                            d.search.value = '';
                        }
                }
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
            columnDefs: columnDefs,
            createdRow: function( nRow, aData, iDataIndex ) {
                // Update name in first column to link
                var name=$('td:eq(0)', nRow).html();
                if(name == ''){name = "No Name"};
                var sn=$('td:eq(1)', nRow).html();
                var link = mr.getClientDetailLink(name, sn, '#tab_findmymac');
                $('td:eq(0)', nRow).html(link);

                // Status
                var status=$('td:eq(3)', nRow).text();
                status = status == 'Enabled' ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status === 'Disabled' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(3)', nRow).html(status)

                // add_time                
                var date = $('td:eq(6)', nRow).text();
	        	if(date){
		        	$('td:eq(6)', nRow).html('<span title="' + moment((date * 1000)).fromNow() + '">'+moment((date * 1000)).format('llll')+'</span>');
	        	}
            }
        } );
    } );
</script>

<?php $this->view('partials/foot'); ?>
