function syncLootSheet() {
	var data = {};
	data.sheetID = sheetID;
	data.entries = [];
	data.totalIsk = $('#totalIsk').val();
	if (isNaN(data.totalIsk)) data.totalIsk = 0;
	data.totalSites = $('#sitesRan').val();
	$('tr.entry').each(function () {
		var entry = {};
		entry.rowid = $(this).attr('name');
		entry.name = $(this).find('input[name=name]').val();
		entry.sites = $(this).find('input[name=sites]').val();
		entry.role = $(this).find('option:selected').text();
		entry.isk = $(this).find('input[name=isk]').val();
		entry.isk = entry.isk.replace(/,/g,'');
		if (isNaN(entry.isk)) entry.isk = 0;
		data.entries.push(entry);
	});

	$.post(location.href+"/update", {data: data}, function (data) {if (readonly) updateTable(eval(("a = "+data)));});
}
var lastSitesRan;

updateTable = function(data) {
	var sheet = data;

	$('#totalIsk').val(sheet.totalIsk);
	$('#sitesRan').val(sheet.totalSites);
	lastSitesRan = sheet.totalSites;

	for (var x=0; x<sheet.entries.length ; x++) {
		var exists = false;
		var element;
		var entry = sheet.entries[x];

		if ($('tr[name='+entry.rowid+']').length != 0) {
			element = $('tr[name='+entry.rowid+']');	
			exists = true;
		} else {
			element = $('.template').find('tr').clone().first();
			element.addClass('entry');
		}

		element.attr('name', entry.rowid);
		element.find('input[name=name]').val(entry.name);
		element.find('input[name=sites]').val(entry.sites);
		element.find('option:contains("'+entry.role+'")').prop('selected', true);

		if (!exists) {
			element.insertBefore('tr:last');
			$('.template').find('tr.entry').remove();
			element.find('input,select').each(function () {
				$(this).change(function () {
					updateIsk();
				}).keyup(function () {
					updateIsk();
				});
			;});
		}

	}
	updateIsk();
};

$('#sitesRan').change(function (ev) {
	var newval = $('#sitesRan').val();
	var diff = newval - lastSitesRan;
	lastSitesRan = newval;
	$('tr.entry').each(function () {
		var active = $(this).find('input[type=checkbox]').prop('checked');

		if (active) {
			var ov = $(this).find('input[name=sites]').val()
			$(this).find('input[name=sites]').val(parseInt(ov)+parseInt(diff));
		}
	})

	
});

$('input').change(function () {
	updateIsk();
});
$('input').keyup(function () {
	$(this).trigger('change');
});
$('select').click(function () {
	$(this).trigger('change');
})

var addCommas = function (val) {
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
    }
    return val;
}

var updateIsk = function () {
	var total = parseInt($('#totalIsk').val());
	var sitenumber = parseInt($('#sitesRan').val()); 
	var totalpoints = 0;
	var points = {};

	var findersfee = Math.floor(Math.min(50000000, total*.05));
	var corpcut = Math.floor(total*.05);
	total -= findersfee;
	total -= corpcut;

	$('#corpCut').val(addCommas(corpcut));

	$('tr.entry').each(function () {
		var sites = $(this).find('input[name=sites]').val();
		var share = $(this).find('option:selected').val();
		var mypoints = sites*share;
		totalpoints += mypoints;

		console.log(share);
		if (share == 0) {
			$(this).find('input[name=isk]').val(addCommas(findersfee));
		} else {
			points[$(this).attr('name')] = mypoints;
		}
	});

	for (rowid in points) {
		var newisk = Math.floor((points[rowid]/totalpoints)*total);
		if (isNaN(newisk)) newisk = 0;
		$('tr[name='+rowid+']').find('input[name=isk]').val(addCommas(newisk));
	}





}

var addMember = function () {
	syncLootSheet();
	$.get(location.href+"/addMember", function (data) {updateTable(eval(("a = "+data)));})
}

var togglePaid = function (status, set) {
	syncLootSheet();
	var proof = $('#proof').val();
	var s = "unset";
	if (set) s="set";
	$.post(location.href+"/update",  {action: "togglePaid", status: status, proof: proof, mode: s}, function () {location.reload()});
}

function ExportEmails() {
	var EveMail='';
    var list = $('.loot-name');
		var listCount = list.length;
		var jq = $([1]);  
		for( var i = 0; i < listCount; i++) {
		    jq.context = jq[0] = list[i];
		    if (jq.val()!=null) {
		    	var thisEmail=jq.val();
			    	if (thisEmail) {
				    		if (EveMail) {
				    			EveMail=EveMail + ',' +thisEmail;
				    		} else {
				    			EveMail=thisEmail;
				    		}
			      } 
				}
		}
  copyToClipboard(EveMail);
}

function ExportTabDel() {
	var TabDel='';
    var list = $('.entry');
		var listCount = list.length;
		var jq = $([1]);  
		for( var i = 0; i < listCount; i++) {
		    jq.context = jq[0] = list[i];
			     var name = $(jq).find(".loot-name").val();
			     var role = $(jq).find(".fused-select option:selected").text();
			     var sites = $(jq).find(".loot-sites").val();
			     var isk= $(jq).find(".loot-isk").val();
				    		if (TabDel) {
				    				if (name){
			     						TabDel=TabDel + name+'\t'+role+'\t'+sites+'\t'+isk+'\r'
				    				}
				    		} else {
			     				TabDel=name+'\t'+role+'\t'+sites+'\t'+isk+'\r'
				    		}
		}
 copyToClipboard(TabDel);
}


function copyToClipboard(text) {
  window.prompt("Copy to clipboard: Ctrl+C, Enter", text);
}
