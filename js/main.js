gsap.registerPlugin(ScrollTrigger);
ScrollTrigger.config({autoRefreshEvents: "visibilitychange,DOMContentLoaded,load"});
let mm = gsap.matchMedia();

document.addEventListener('DOMContentLoaded', function() {

    let fadeupSet = { opacity: 0, y: 80, duration: 0.3 };
    let fadeupSetTo = { opacity: 1, y: 0, duration: 0.3 };

    let ww = $(window).width();
    let wh = $(window).height();

    let circle = window.innerWidth <= 480 ? 'circle(160%)' :
                window.innerWidth <= 768 ? 'circle(140%)' :
                'circle(620%)';
    let opcityNone = window.innerWidth <= 768 ? 1 : 0;
    let yNone = window.innerWidth <= 768 ? 0 : -50;

    gsap.timeline({
        scrollTrigger:{
            trigger:'.main-bottom',
            start:'top 80%',
            toggleActions:'play none none reverse',
        }
    })
    .from('.main-bottom .circleBx',{opacity:0,y:100,duration:0.3})
    .from('.main-bottom .circle-txt p',{...fadeupSet})
    .to('.main-bottom .circleBx', { opacity: 1, y:0, duration: 0.3})
    .to('.main-bottom .circle-txt p', { opacity: 1, y:0, duration: 0.3}, "-=0.1")

    let s4tl01 = gsap.timeline({
        scrollTrigger:{
            trigger:'.main-bottom',
            start:'top top',
            end:`+=${wh * 4}`,
            scrub:1,
        }
    })
    .to('.main-bottom .circle',{clipPath:circle, duration:0.2},0)

    ScrollTrigger.create({
        trigger:'.main-bottom',
        start:'top top',
        endTrigger:'#section12',
        end:'top bottom',
        // pin:'.main-bottom .pinBx',
        // pinSpacing:false,
        scrub: 1,
    })

    gsap.timeline({
        scrollTrigger:{
            trigger:'.main-bottom .bottom',
            start:'top 100%',
            toggleActions:'play none none reverse',
        }
    })
    .from('.circle .reels-section .reels1',{opacity:0,y:-100,duration:0.3})
    .from('.circle .reels-section .reels2',{opacity:0,y:100,duration:0.3})
    .from('.circle .reels-section .reels3',{opacity:0,x:100,duration:0.3})
    .to('.circle .reels-section .reels1', { opacity: 1, y:0, duration: 0.3})
    .to('.circle .reels-section .reels2', { opacity: 1, y:0, duration: 0.3})
    .to('.circle .reels-section .reels3', { opacity: 1, x:0, duration: 0.3})
    .to('.main-bottom .circle-txt p', { opacity: 0, y:-50, duration: 0.2}, "-=0.8")


    $(window).resize(function () {
        clearTimeout(timeoutClear);
        timeoutClear = setTimeout(function () {
            ScrollTrigger.refresh();
        }, 200);
    });


    window.getTriggerOffsetByDuration = (timeline, multiplier = 1000) => {
        return 'top+=' + timeline.totalDuration() * multiplier + ' top';
    };
});
