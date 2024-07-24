// index.tsx
import React from 'react';
import Input from '../Input';
import { DatePickerContainer } from './datepicker.styled';
import DatepickerProps from './datepicker.type';

const DatePicker: React.FC<DatepickerProps> = ({ ...props }) => {
  return (
    <DatePickerContainer>
      <Input type="date" {...props} />
    </DatePickerContainer>
  );
};

export default DatePicker;
