var config = {
    paths: {
        slick: 'Magento_Theme::slick/slick.min',
        // "popper":"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min",
        // "bootstrap4":"https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min"
        // bootstrap: 'js/bootstrap.min'
    },
    shim: {
        slick: {
            deps: ['jquery']
        }
    }
};