
function Modal () {
	this.VERSION = '0.1';
	this.currentUrl = '';
	this.lastScrollPos = 0;
	this.lisenters = {};
	this.urlPath = '';
	this.modalOpen = false;
	this.href = '';
};

Modal.prototype.loadUrl = function(url, success) {
	
	if(url == this.currentUrl) {
		return;
	}
	
	this.currentUrl = url;
	
	jQuery.ajax({
		url: url,
		success: success
	});
};


Modal.prototype.init = function() {
	
	window.modal = this;
	
	this.href = window.location.href;
	this.refreshModalLinks();
	
	jQuery(window).on('popstate', function() {
		if(window.modal.modalOpen){
			window.modal.close();
		} else{
			modal.show(window.location.href);
		}
	});
	
};

Modal.prototype.refreshModalLinks = function() {
	
	var modal = this;
	
	jQuery(".load-modal").off('click');
	jQuery(".load-modal").click(function(){
		
		if(jQuery(window).width() <= 600) return true;
		
		var url = "";
		
		if(jQuery(this).data("url")){
			url = jQuery(this).data("url");
		}else{
			url = jQuery(this).attr("href");
		}
		
		if(url && url.length > 0){
			
			url += '?tmpl=modal';
			modal.show(url);
		}
		return false;
	});
};


Modal.prototype.show = function(url) {

	var modal = this;
	var success = function(data){
		modal.displayModal(data);
	};
	
	this.displayBackground();
	this.urlPath = url;
	this.loadUrl(url, success);
};

Modal.prototype.showWithHtml = function(html) {
	this.displayBackground();
	this.displayModal(html);
};

Modal.prototype.displayModal = function(data) {
	
	if(window.history && window.history.pushState)
		window.history.pushState({},"", this.urlPath);
	
	this.createModal(data);
	modal.refreshModalLinks();
};

Modal.prototype.createModal = function(data) {

	if(!jQuery('.modal-wrapper').length) {
		jQuery('<div class="modal-wrapper" id="modal-wrapper"></div>').appendTo('body');
		this.refreshModal(data);
	} else {
		var modal = this;
		jQuery('.modal-wrapper').fadeOut(500, function(){
			modal.refreshModal(data);	
		});
	}
};

Modal.prototype.refreshModal = function(data) {

	jQuery("#modal-wrapper").html(data);
	jQuery("#modal-wrapper").append('<div class="modal-bg-clickable" id="modal-bg-clickable"></div>');
	this.addCloseButton();
	
	var modal = this;
	jQuery('#modal-bg-clickable').click(function(){jQuery("#modal-close").click();});
	
	var offset = jQuery(window).scrollTop();
	
	jQuery('.modal-wrapper').css("top", offset + "px");
	jQuery('.modal-wrapper').hide();
	jQuery('.modal-wrapper').fadeIn(250);
	
	jQuery('body').addClass("clip");
	
	this.modalOpen = true;
	
	this.fireEvent('loaded', null);
	
};


Modal.prototype.addCloseButton = function() {
	
	if(!jQuery('.modal-close').length) {
		jQuery('<p><a class="modal-close" id="modal-close"><svg width="20" height="20" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve"><polygon style="fill:#00539c" points="21.1,0 12,9.2 2.8,0.1 0,2.9 9.2,12 0.1,21.2 2.9,24 12,14.8 21.2,23.9 24,21.1 14.8,12 23.9,2.8 "/></svg></a></p>').appendTo('.modal');
	}

	jQuery("#modal-close").off('click');
	var modal = this;
	jQuery("#modal-close").click(function(){window.history.back()});
	
};

Modal.prototype.displayBackground = function() {

	if(!jQuery('.modal-backdrop').length) {
		jQuery('<div class="modal-backdrop" id="modal-backdrop"></div>').appendTo('body');
		jQuery('#modal-backdrop').fadeIn(250);
		var modal = this;
		jQuery('#modal-backdrop').click(function(){modal.close();});
	}
};

Modal.prototype.close = function() {
	
	jQuery('.modal-backdrop').fadeOut(250, function( element ){
		jQuery(".modal-backdrop").remove();
		jQuery(".modal-wrapper").remove();
		
		jQuery('body').removeClass("clip");
	});
	
	jQuery('.modal-wrapper').fadeOut(250);
	
	this.modalOpen = false;
	
	this.fireEvent("close", null);
	this.currentUrl = "";
	this.clearListeners();
};

Modal.prototype.fireEvent = function(type, event) {
	
	if(!this.lisenters[type]) {
		return;
	}	

	if (this.lisenters[type] instanceof Array){
		var listeners = this.lisenters[type];
		for (var i=0, len=listeners.length; i < len; i++){
			listeners[i].call(this, event);
		}
	}
	
	return;
};

Modal.prototype.addEventListener = function(type, listener) {
	
	if(!this.lisenters[type]) {
		this.lisenters[type] = [];
	}
	
	this.lisenters[type].push(listener);
};

Modal.prototype.clearListeners = function() {
	this.lisenters = {};
};