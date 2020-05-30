export default class {
    constructor($el) {
        this.ddBtn = $el;
        this.isOpen = false;

        this.ddPanel = $(this.ddBtn).closest('.card-header').next('.details-panel')[0];
        $(this.ddPanel).slideUp();
        $(this.ddBtn).on('click', ()=>{
            this.handleClick();
        } );
        console.log(this);
    }

    handleClick() {
        $(this.ddPanel).slideToggle();
        // if (this.isOpen) {
        //     $(this.ddPanel).slideUp();
        //     this.isOpen = false;
        //     return;
        // }
        // console.log("After isOpen bool")
        // $(this.ddPanel).slideDown();
        // this.isOpen = true;
    }
}