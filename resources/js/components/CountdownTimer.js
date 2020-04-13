export default class{
     constructor($el) {
        this.$timerContainer = $el;
        this.timePathRemaining = $(this.$timerContainer).find('.base-timer__path-remaining');
        this.$baseTimerLabel = $(this.$timerContainer).find(".base-timer__label");
        this.$baseTimerPathRemaining = $(this.$timerContainer).find(".base-timer__path-remaining");

        // Start with an initial value of 20 seconds
        this.TIME_LIMIT = $($el).data('time');
        this.WARNING_THRESHOLD = this.TIME_LIMIT / 2;
        this.ALERT_THRESHOLD = this.TIME_LIMIT / 4;

        // Initially, no time has passed, but this will count up
        // and subtract from the TIME_LIMIT
        this.timePassed = 0;
        this.timeLeft = this.TIME_LIMIT;
        this.timerInterval = null;

        this.FULL_DASH_ARRAY = 283;
        this.COLOR_CODES = {
            info: {
                color: "green"
            },
            warning: {
                color: "orange",
                threshold: this.WARNING_THRESHOLD
            },
            alert: {
                color: "red",
                threshold: this.ALERT_THRESHOLD
            }
        };
        this.remainingPathColor = this.COLOR_CODES.info.color;
        $(this.timePathRemaining).addClass(this.COLOR_CODES.info.color);

        $(this.$baseTimerLabel).html( 'Start...');

         $(this.$timerContainer).on('click', ()=>{
             this.startTimer();
             $(this.$timerContainer).off('click');
         })
    }

    // Divides time left by the defined time limit.
    calculateTimeFraction() {
        return (this.timeLeft - 1) / this.TIME_LIMIT;
    }

    formatTimeLeft(time) {
        // The largest round integer less than or equal to the result of time divided being by 60.
        const minutes = Math.floor(time / 60);
        // Seconds are the remainder of the time divided by 60 (modulus operator)
        let seconds = time % 60;
        // If the value of seconds is less than 10, then display seconds with a leading zero
        if (seconds < 10) {
            seconds = `0${seconds}`;
        }
        // The output in MM:SS format
        return `${minutes}:${seconds}`;
    }

// Update the dasharray value as time passes, starting with 283
     setCircleDasharray() {
        const circleDasharray = `${(
            this.calculateTimeFraction() * this.FULL_DASH_ARRAY
        ).toFixed(0)} ${this.FULL_DASH_ARRAY}`;
        $(this.$baseTimerPathRemaining).attr("stroke-dasharray", circleDasharray);
    }

    startTimer() {
         this.timerInterval = setInterval(() => {
             // The amount of time passed increments by one
            this.timePassed = this.timePassed += 1;
            this.timeLeft = this.TIME_LIMIT - this.timePassed;

            this.setCircleDasharray();
            this.setRemainingPathColor(this.timeLeft);

            $(this.$baseTimerLabel).html(this.formatTimeLeft(this.timeLeft));

            if(this.timeLeft === 0){
                clearInterval(this.timerInterval);
                $(this.$baseTimerLabel).html('End!')
             }
        }, 1000);
    }

    setRemainingPathColor(timeLeft) {
        const { alert, warning, info } = this.COLOR_CODES;
        // If the remaining time is less than or equal to a quarter of total, remove the "warning" class and apply the "alert" class.
        if (timeLeft <= alert.threshold) {
            $(this.timePathRemaining).removeClass(warning.color).addClass(alert.color);
            // If the remaining time is less than or equal to half of total , remove the base color and apply the "warning" class.
        } else if (timeLeft <= warning.threshold) {
             $(this.timePathRemaining).removeClass(info.color).addClass(warning.color);
        }
    }

}















