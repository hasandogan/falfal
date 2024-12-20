import { SelectHTMLAttributes } from 'react';

interface Option {
  value: string | number;
  label: string;
}

type SelectProps = Omit<SelectHTMLAttributes<HTMLSelectElement>, 'type'> & {
  label?: string;
  options: Option[];
};

export default SelectProps;
