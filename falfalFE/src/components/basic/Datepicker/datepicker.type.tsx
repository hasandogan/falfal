import { InputHTMLAttributes } from 'react';

type DatepickerProps = Omit<InputHTMLAttributes<HTMLInputElement>, 'type'> & {
  type?: 'text' | 'password' | 'number' | 'email' | 'date';
  label?: string;
};

export default DatepickerProps;
