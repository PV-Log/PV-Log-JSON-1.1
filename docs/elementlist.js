
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
                '<span style="font-weight:bold">v1.0.0</span> &nbsp; <small>(2015-04-16)</small>'+
                '</li></ul>');

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

