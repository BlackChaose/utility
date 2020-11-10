(function ( $ ) {
$.fn.sms_symbol_counter = function(action) {
	function getSMSPartsU(message){
		if(message.length <= 70) {
			return 1;
		}
		else {
			return Math.trunc(message.length / 67) + 1.0;
		}
	};
	function getSMSPartsG(message){
		if(message.length <= 160) {
			return 1;
		}
		else {
			return Math.trunc(message.length / 153) + 1.0;
		}
	};
	if(action == "init"){
		this.after("<div id=\"mes_counter\">0:0</div>");
	}
	/*for Unicode (67 symbols for segment)*/	
	if(action == "countUnicode"){
		$("#mes_counter").empty();
		$("#mes_counter").append(getSMSPartsU(this.val())+":"+this.val().length);
	}
	/*for GSM (153 symbols for segment)*/	
	if(action == "countGSM"){
		$("#mes_counter").empty();
		$("#mes_counter").append(getSMSPartsG(this.val())+":"+this.val().length);
	}
    return this;
}
}( jQuery ));