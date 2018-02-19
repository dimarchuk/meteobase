$(document).ready(function () {

    /**
     * Prototype for correct date display
     * @returns {string}
     */
    Date.prototype.yyyymmdd = function () {

        var mm = this.getMonth() + 1; // getMonth() is zero-based
        var dd = this.getDate();

        return [
            this.getFullYear() + '-' +
            (mm > 9 ? '' : '0') + mm + '-' +
            (dd > 9 ? '' : '0') + dd
        ].join('');
    };

    var date = new Date();

    /**
     * For set date to selected inputs
     * @param date
     * @param input
     */
    function setDate(date, input) {
        var dateControl = document.querySelector(input);
        dateControl.value = date.yyyymmdd();
        dateControl.max = date.yyyymmdd();
    }
    setDate(date, 'input[name="dateFrom"]');
    setDate(date, 'input[name="dateTo"]');

    /**
     * Select more then one elements
     */
    jQuery('option').mousedown(function (e) {
        e.preventDefault();
        jQuery(this).toggleClass('selected');

        jQuery(this).prop('selected', !jQuery(this).prop('selected'));
        return false;
    });
});