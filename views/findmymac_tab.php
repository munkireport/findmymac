<h2 data-i18n="findmymac.title"></h2>
  
<div id="findmymac-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<div id="findmymac-view" class="row hide">
  <div class="col-md-4">
    <table id="findmymac-table" class="table table-striped">
      <tr>
      </tr>
    </table>
  </div>
</div>

<script>
$(document).on('appReady', function(e, lang) {

  // Get findmymac data
  $.getJSON( appUrl + '/module/findmymac/get_data/' + serialNumber, function( data ) {
    if( Object.keys(data).length === 0 ){
      $('#findmymac-msg').text(i18n.t('findmymac.not_found'));
    }
    else{
      // Hide
      $('#findmymac-msg').text('');
      $('#findmymac-view').removeClass('hide');
                  
        // Add data
        for (var key in data) {
            if (key == "serial_number" || key == "id" || key == ""){
                continue;
            }

            if (key == "status"){
                // Update the tab badge count
                $('#findmymac-cnt').text(data.status);
            }
            
            $('#findmymac-table tbody').append(
                $('<tr/>').append(
                    $('<th/>').text(i18n.t("findmymac.listing."+key)),
                    $('<td/>').text(data[key])
                )
            )
        }
    }

  });
});
  
</script>