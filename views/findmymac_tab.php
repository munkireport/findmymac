<h2 data-i18n="findmymac.widget.title"></h2>
  
<div id="findmymac-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>
  
<div id="findmymac-view" class="row hide">
  <div class="col-md-7">
    <table id="findmymac-table" class="table table-striped">
      <tr>
        <th data-i18n="findmymac.key"></th>
        <th data-i18n="findmymac.value"></th>
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
        $('#findmymac-table tbody').append(
            $('<tr/>').append(
                $('<th/>').text(key),
                $('<td/>').text(data[key])
            )
        )
      }
    }

  });
});
  
</script>