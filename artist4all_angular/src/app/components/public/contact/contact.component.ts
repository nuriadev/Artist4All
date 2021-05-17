import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar, MatSnackBarHorizontalPosition, MatSnackBarVerticalPosition } from '@angular/material/snack-bar';
import { UserService } from 'src/app/core/services/user.service';
import { Location } from '@angular/common';
@Component({
  selector: 'app-contact',
  templateUrl: './contact.component.html',
  styleUrls: ['./contact.component.css'],
})
export class ContactComponent implements OnInit {
  constructor(
    private _userService: UserService,
    private _location: Location,
    private _formBuilder: FormBuilder,
    private _snackBar: MatSnackBar,
  ) { }

  contactForm: FormGroup;
  nameSurnamePattern = "[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,50}";
  emailPattern = '^[a-z0-9._%+-]+@[a-z0-9.-]+.[a-z]{2,4}$';
  phonePattern = '^(6|7|9)[ -]*([0-9][ -]*){8}$';
  ngOnInit(): void {
    this.contactForm = this._formBuilder.group(
      {
        name: ['', [Validators.required, Validators.pattern(this.nameSurnamePattern)]],
        surname1: ['', [Validators.required, Validators.pattern(this.nameSurnamePattern)]],
        email: ['' , [Validators.required, Validators.pattern(this.emailPattern)]],
        phone: ['' , [Validators.pattern(this.phonePattern)]],
        bodyMessage: ['', [Validators.required, Validators.maxLength(500)]]
      }
    )
  }

  contador: number = 500;
  showingBodyHint: boolean = false;
  countCharacters(event) {
    this.contador = 500;
    this.contador -= event.target.value.length;
    if (event.target.value.length > 500) this.showingBodyHint = true;
    else this.showingBodyHint = false;
  }

  get name() { return this.contactForm.get('name'); }
  get surname1() { return this.contactForm.get('surname1'); }
  get email() { return this.contactForm.get('email'); }
  get phone() { return this.contactForm.get('phone'); }
  get bodyMessage() { return this.contactForm.get('bodyMessage'); }

  showingNameHint: boolean = false;
  showNameHint() {
    if (!this.showingNameHint) this.showingNameHint = true;
    else this.showingNameHint = false;
  }

  showingSurname1Hint: boolean = false;
  showSurname1Hint() {
    if (!this.showingSurname1Hint) this.showingSurname1Hint = true;
    else this.showingSurname1Hint = false;
  }

  isValidFormSubmitted = null;

  sendContactForm() {
    this.isValidFormSubmitted = false;
    if (this.contactForm.invalid) {
      return;
    }
    this.message = 'Mensaje enviado.';
    this.isValidFormSubmitted = true;
    let formValues: any = this.contactForm.value;
    this._userService.sendContactForm(formValues).subscribe(
      (result) => {
        this._location.back();
        this.openSnackBar(this.message);
      }, (error) => {
        console.log(error)
    });
  }

  message: string;
  horizontalPosition: MatSnackBarHorizontalPosition = 'right';
  verticalPosition: MatSnackBarVerticalPosition = 'bottom';
  openSnackBar(message: string) {
    this._snackBar.open(message, 'OK', { duration: 2000, horizontalPosition: this.horizontalPosition, verticalPosition: this.verticalPosition });
  }
}
