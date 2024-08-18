import { RotatingLines } from 'react-loader-spinner';
import * as Styled from './LoadingContainer.styled';

const LoadingContainer = () => {
  return (
    <Styled.LoadingContainer>
      <RotatingLines
        visible={true}
        strokeColor={'#ffffff'}
        strokeWidth="5"
        animationDuration="2"
      />
    </Styled.LoadingContainer>
  );
};
export default LoadingContainer;
