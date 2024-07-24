// datepicker.styled.tsx
import styled from 'styled-components';

const DatePickerContainer = styled.div`
  display: inline-block;
  position: relative;
`;

const DatePickerInput = styled.input`
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
`;

const Calendar = styled.div`
  position: absolute;
  top: 100%;
  left: 0;
  background: white;
  border: 1px solid #ccc;
  z-index: 1000;
`;

export { Calendar, DatePickerContainer, DatePickerInput };
