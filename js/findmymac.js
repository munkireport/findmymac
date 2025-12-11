var formatFindmyStatus = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == 'Enabled' ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
    (colvar === 'Disabled' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : colvar)
    col.html(colvar)
}

var formatFindmytimestampToMoment =  function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    if (colvar > 0){
        var date = new Date(parseInt(colvar) * 1000);
        col.html('<span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span>')
    }
}