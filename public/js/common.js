function notification(text, type) {
    noty({
        theme: 'bootstrapTheme',
        text: text,
        type: type,
        timeout: 3000,
        layout: 'topRight',
        closeWith: ['button', 'click'],
        animation: {
            open: {height: 'toggle'},
            close: {height: 'toggle'},
            easing: 'swing',
            speed: 500
        }
    });
}
