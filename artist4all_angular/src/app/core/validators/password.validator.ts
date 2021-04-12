import { FormGroup, ValidationErrors, ValidatorFn } from "@angular/forms"

export const matchingPasswords: ValidatorFn = (control: FormGroup): ValidationErrors | null => {
  const password = control.get("password")
  const passwordConfirm = control.get("passwordConfirm")
  if (passwordConfirm.value != '') {
    return password.value === passwordConfirm.value ? null : { notMatching: true }
  }
}
