$(document).ready(function() {
  var engine, remoteHost, template, empty;

  $.support.cors = true;

  remoteHost = 'https://typeahead-js-twitter-api-proxy.herokuapp.com';
   header =  Handlebars.compile($("#header-template").html());
  template = Handlebars.compile($("#result-template").html());
  empty = Handlebars.compile($("#empty-template").html());

  engine = new Bloodhound({
    identify: function(o) { return o.id; },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
    dupDetector: function(a, b) { return a.id === b.id; },
    prefetch: webPath + '/home/prefetch',
    remote: {
      url: webPath + '/home/typeahead?q=%QUERY',
      wildcard: '%QUERY'
    }
  });

  // ensure default users are read on initialization
  engine.get('1', '2', '3', '4', '5');

  function engineWithDefaults(q, sync, async) {
   alert("FFf");
    if (q === '') {

      sync(engine.get('1', '2', '3', '4', '5'));
      async([]);
    }

    else {
      engine.search(q, sync, async);
    }
  }

 jQuery( "#typeahead" ).keyup(function() {

  var datata = jQuery(this).val();
 //alert("");
 // console.log( "Handler for .keypress() called." );
 if(jQuery(this).val() != ''){
 
}
});
 //function engi() {
  $('#typeahead').typeahead(null,{
    hint: $('.Typeahead-hint'),
    menu: "",
   minLength: 0,
    prefetch: '../data/nba.json',
    classNames: {
      //open: 'is-open',
      empty: 'is-empty',
      cursor: 'is-active',
      suggestion: 'Typeahead-suggestion',
      selectable: 'Typeahead-selectable'
    }
  }, {

    source: engineWithDefaults,
    name: 'slug',
    display: 'name',
    limit: 3,
    templates: {
       footer:header,
      
      suggestion: template,
       empty: empty
    }
  })
  .on('typeahead:asyncrequest', function() {
    $('.Typeahead-spinner').show();
  })
  .on('typeahead:asynccancel typeahead:asyncreceive', function() {
    $('.Typeahead-spinner').hide();
  })
  .on('typeahead:selected', function(event, data){
      $('#slug').val(data.slug);
      
      $('form[name=search]').submit();
  });
//}

});
