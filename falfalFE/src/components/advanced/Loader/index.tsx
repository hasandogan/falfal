import * as Styled from './loader.styled';
const Loader = () => {
  return (
    <Styled.Loader>
      <div className="text">FalFal</div>
      <svg
        width="250"
        height="250"
        viewBox="0 0 250 250"
        className="circular-progress"
      >
        <circle className="bg"></circle>
        <circle className="fg"></circle>
      </svg>
    </Styled.Loader>
  );
};

export default Loader;
