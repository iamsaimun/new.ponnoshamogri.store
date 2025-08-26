<style>
.custom-headline {
    display: block;
    font-size: 45px;
    line-height: 1.2
}


.custom-headline--style-highlight svg {
    height: calc(100% + 20px);
    left: 50%;
    overflow: visible;
    position: absolute;
    top: 50%;
    transform: translate(-50%,-50%);
    width: calc(100% + 20px)
}

.custom-headline--style-highlight svg path {
    stroke: red;
    stroke-width: 9;
    fill: none;
    opacity: 0;
    stroke-dasharray: 0 1500;
    transition: .3s
}

.custom-headline.e-animated svg path {
    animation: custom-headline-dash forwards;
    animation-duration:  1.2s;
    animation-iteration-count: 1
}

.custom-headline.e-animated svg path:nth-of-type(2) {
    animation-delay:  1.2s
}

.custom-headline.e-hide-highlight svg path {
    opacity: 1;
    stroke-dasharray: 1500 1500;
    animation: hide-highlight .4s forwards;
    animation-iteration-count: 1
}

@keyframes custom-headline-dash {
    0% {
        stroke-dasharray: 0 1500;
        opacity: 1
    }

    to {
        stroke-dasharray: 1500 1500;
        opacity: 1
    }
}

@keyframes hide-highlight {
    /* 0% {
        opacity: 1;
        stroke-dasharray: 1500 1500
    }

    to {
        filter: blur(10px);
        opacity: 0
    } */
     0% {
        stroke-dasharray: 0 1500;
        opacity: 1
    }

    to {
        stroke-dasharray: 1500 1500;
        opacity: 1
    }
}


</style>
