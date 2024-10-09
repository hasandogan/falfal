import { Vortex } from 'react-loader-spinner';
import * as Styled from './loader.styled';

const Loader = () => {
  return (
    <Styled.Loader>
      <Vortex
        visible={true}
        height="100%"
        width="100%"
        ariaLabel="vortex-loading"
        wrapperStyle={{}}
        wrapperClass="vortex-wrapper"
        colors={[
          '#a485e3',
          '#1fcfbc',
          '#08313a',
          '#a485e3',
          '#1fcfbc',
          '#08313a',
        ]}
      />
      <div className="logo"></div>
    </Styled.Loader>
  );
};
export default Loader;
