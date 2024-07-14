import styled from 'styled-components';

const Loader = styled.div`
  position: absolute;
  top: 50%;
  transform: translateY(50%);
  left: 0;
  right: 0;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  .circular-progress {
    --size: 250px;
    --half-size: calc(var(--size) / 2);
    --stroke-width: 20px;
    --radius: calc((var(--size) - var(--stroke-width)) / 2);
    --circumference: calc(var(--radius) * 2 * 3.14);
    --dash: calc((var(--progress) / 100) * var(--circumference));
    animation: progress-animation 5s linear 0s 1 forwards;
  }

  .circular-progress circle {
    cx: var(--half-size);
    cy: var(--half-size);
    r: var(--radius);
    stroke-width: var(--stroke-width);
    fill: none;
    stroke-linecap: round;
  }

  .circular-progress circle.bg {
    stroke: transparent;
  }

  .circular-progress circle.fg {
    transform: rotate(-90deg);
    transform-origin: var(--half-size) var(--half-size);
    stroke-dasharray: var(--dash) calc(var(--circumference) - var(--dash));
    transition: stroke-dasharray 0.3s linear 0s;
    stroke: #ffffff;
    filter: blur(3px);
  }
  .text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 24px;
    color: #ffffff;
    pointer-events: none;
    font-size: 40px;
    line-height: 22px;
    text-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  }

  @keyframes progress-animation {
    from {
      --progress: 0;
    }
    to {
      --progress: 100;
    }
  }
`;

export { Loader };
