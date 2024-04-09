function change_input(element) {
    //select parent
    var container = element.parentNode;
    for (i = 0; i < container.children.length; i++) {
        if (container.children[i] == element) {
            if (i == 5) {
                document.getElementById('submiter').click();
            }
            container.children[i + 1].focus();
            break;
        }
    }
}