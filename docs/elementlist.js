
var ApiGen = ApiGen || {};
ApiGen.elements = [["c","PVLog\\Classes\\Json\\EnergyMeter"],["c","PVLog\\Classes\\Json\\FeedIn"],["c","PVLog\\Classes\\Json\\GridConsumption"],["c","PVLog\\Classes\\Json\\Helper"],["c","PVLog\\Classes\\Json\\Instance"],["c","PVLog\\Classes\\Json\\Inverter"],["c","PVLog\\Classes\\Json\\Irradiation"],["c","PVLog\\Classes\\Json\\Json"],["c","PVLog\\Classes\\Json\\Plant"],["c","PVLog\\Classes\\Json\\PowerSensor"],["c","PVLog\\Classes\\Json\\Properties"],["c","PVLog\\Classes\\Json\\SelfConsumption"],["c","PVLog\\Classes\\Json\\Set"],["c","PVLog\\Classes\\Json\\Strings"],["c","PVLog\\Classes\\Json\\Temperature"],["c","PVLog\\Classes\\Json\\TotalConsumption"],["c","PVLog\\PVLog"]];

var diagram;

function resizeDiagram () {
    var w = $('#content').width();
    diagram.width(''); /* Reset width! */
    if (diagram.width() > w) diagram.width(w);
}

$(function() {

    /* Append version to navigation menu */
    $('#navigation')
        .append('<ul><li style="color:#aa3300">'+
                '<span style="font-weight:bold">v1.1.0</span> &nbsp; <small>(2015-04-21)</small>'+
                '</li></ul>');

    $('head').append('<link rel="shortcut icon" href="data:image/png;base64,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" type="image/x-icon">');

    /* Pimp footer with copyright */
    var f = $('#footer');
    f.html(f.html().replace('API', '&copy; <a href="http://pv-log.com">PV-Log.com, Top50-Solar</a> | '));

    /* Show diagram only on overview and tree */
    if (location.href.match(/\/(index|tree|$)/)) {

        /* Pointer to content div */
        var content = $('#content');

        /* Append H3 */
        $('<h3/>').text('Class diagram').appendTo(content);

        /* Build diagram img */
        diagram = $('<img/>', {src: 'resources/classes.svg'}).one('load', resizeDiagram);

        /* Append wrapper DIV with SVG image */
        $('<div/>').addClass('diagram').append(diagram).appendTo(content);

        $(window).on('resize', resizeDiagram);
    }

});

