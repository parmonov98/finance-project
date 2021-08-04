  <div wire:loading.delay class="loading">
    <div class="flex items-center justify-center w-full fixed opacity-80 z-50 top-0 left-0 h-screen">
      <div class="la-line-scale" style="color:#0b96fb;">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
  </div>

  @push('css')


    <style>


    .loading {
    position: fixed;
    z-index: 1051;
    height: 2em;
    width: 2em;
    overflow: show;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    }

    /* Transparent Overlay */
    .loading:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
        background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

    background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
    }
/*!
* Load Awesome v1.1.0 (http://github.danielcardoso.net/load-awesome/)
* Copyright 2015 Daniel Cardoso <@DanielCardoso>
* Licensed under MIT
*/
      .la-line-scale,
      .la-line-scale>div {
        position: relative;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
      }

      .la-line-scale {
        display: block;
        font-size: 0;
        color: #fff;
      }

      .la-line-scale.la-dark {
        color: #333;
      }

      .la-line-scale>div {
        display: inline-block;
        float: none;
        background-color: currentColor;
        border: 0 solid currentColor;
      }

      .la-line-scale {
        width: 40px;
        height: 32px;
      }

      .la-line-scale>div {
        width: 4px;
        height: 32px;
        margin: 2px;
        margin-top: 0;
        margin-bottom: 0;
        border-radius: 0;
        -webkit-animation: line-scale 1.2s infinite ease;
        -moz-animation: line-scale 1.2s infinite ease;
        -o-animation: line-scale 1.2s infinite ease;
        animation: line-scale 1.2s infinite ease;
      }

      .la-line-scale>div:nth-child(1) {
        -webkit-animation-delay: -1.2s;
        -moz-animation-delay: -1.2s;
        -o-animation-delay: -1.2s;
        animation-delay: -1.2s;
      }

      .la-line-scale>div:nth-child(2) {
        -webkit-animation-delay: -1.1s;
        -moz-animation-delay: -1.1s;
        -o-animation-delay: -1.1s;
        animation-delay: -1.1s;
      }

      .la-line-scale>div:nth-child(3) {
        -webkit-animation-delay: -1s;
        -moz-animation-delay: -1s;
        -o-animation-delay: -1s;
        animation-delay: -1s;
      }

      .la-line-scale>div:nth-child(4) {
        -webkit-animation-delay: -.9s;
        -moz-animation-delay: -.9s;
        -o-animation-delay: -.9s;
        animation-delay: -.9s;
      }

      .la-line-scale>div:nth-child(5) {
        -webkit-animation-delay: -.8s;
        -moz-animation-delay: -.8s;
        -o-animation-delay: -.8s;
        animation-delay: -.8s;
      }

      .la-line-scale.la-sm {
        width: 20px;
        height: 16px;
      }

      .la-line-scale.la-sm>div {
        width: 2px;
        height: 16px;
        margin: 1px;
        margin-top: 0;
        margin-bottom: 0;
      }

      .la-line-scale.la-2x {
        width: 80px;
        height: 64px;
      }

      .la-line-scale.la-2x>div {
        width: 8px;
        height: 64px;
        margin: 4px;
        margin-top: 0;
        margin-bottom: 0;
      }

      .la-line-scale.la-3x {
        width: 120px;
        height: 96px;
      }

      .la-line-scale.la-3x>div {
        width: 12px;
        height: 96px;
        margin: 6px;
        margin-top: 0;
        margin-bottom: 0;
      }

      /*
                     * Animation
                     */
      @-webkit-keyframes line-scale {

        0%,
        40%,
        100% {
          -webkit-transform: scaleY(.4);
          transform: scaleY(.4);
        }

        20% {
          -webkit-transform: scaleY(1);
          transform: scaleY(1);
        }
      }

      @-moz-keyframes line-scale {

        0%,
        40%,
        100% {
          -webkit-transform: scaleY(.4);
          -moz-transform: scaleY(.4);
          transform: scaleY(.4);
        }

        20% {
          -webkit-transform: scaleY(1);
          -moz-transform: scaleY(1);
          transform: scaleY(1);
        }
      }

      @-o-keyframes line-scale {

        0%,
        40%,
        100% {
          -webkit-transform: scaleY(.4);
          -o-transform: scaleY(.4);
          transform: scaleY(.4);
        }

        20% {
          -webkit-transform: scaleY(1);
          -o-transform: scaleY(1);
          transform: scaleY(1);
        }
      }

      @keyframes line-scale {

        0%,
        40%,
        100% {
          -webkit-transform: scaleY(.4);
          -moz-transform: scaleY(.4);
          -o-transform: scaleY(.4);
          transform: scaleY(.4);
        }

        20% {
          -webkit-transform: scaleY(1);
          -moz-transform: scaleY(1);
          -o-transform: scaleY(1);
          transform: scaleY(1);
        }
      }

    </style>
  @endpush
