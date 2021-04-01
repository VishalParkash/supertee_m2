var config = {
    paths: {
        'slick': 'slick/slick.min',
        // "popper":"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min",
        "bootstrap4":"https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min",
        "fabric":"https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.3.1/fabric.min"
        // bootstrap: 'js/bootstrap.min'
    },
    shim: {
        slick: {
            deps: ['jquery']
        },
        bootstrap4: {
            deps: ['jquery','jquery/ui','Magento_Ui/js/modal/modal']
        },
        fabric: {
            deps: ['jquery']
        }
    }
};