import styled from 'styled-components';

const Loader = styled.div`
  height: 100dvh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  .vortex-wrapper {
    > g {
      > g {
        transform: unset;
      }
    }
  }
  .logo {
    background-image: url('/images/logo.png');
    background-repeat: no-repeat;
    background-position: center center;
    width: 70px;
    height: 70px;
    position: absolute;
    left: 0;
    right: 0;
    margin: 0 auto;
    top: 50%;
    transform: translateY(-50%);
    -webkit-animation: fadein 3s ease-in alternate;
    -moz-animation: fadein 3s ease-in alternate;
    animation: fadein 3s ease-in alternate;
  }
  @keyframes fadein {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }
`;

export { Loader };
