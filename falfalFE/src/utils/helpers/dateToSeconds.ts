export const dateToSeconds = (dateString: string) =>
  new Date(dateString).getTime() / 1000;
