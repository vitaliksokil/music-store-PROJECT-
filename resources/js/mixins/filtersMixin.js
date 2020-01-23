export const filtersMixin = {
    filters: {
        threePoints: function (value) {
            let re = /<.+?>/g ;
            value = value.replace(re,'');
            if (value.length > 20) {
                return value.slice(0, 20) + '...';
            } else {
                return value;
            }
        },
        threePointsTitle:function (value) {
            if (value.length > 10) {
                return value.slice(0, 10) + '...';
            } else {
                return value;
            }
        },
        isVerified: function (val) {
            if (val) {
                return 'VERIFIED';
            } else {
                return "UNVERIFIED";
            }
        },
        isPaid: function (val) {
            if (val) {
                return 'PAID';
            } else {
                return "UNPAID";
            }
        }
    },
};
