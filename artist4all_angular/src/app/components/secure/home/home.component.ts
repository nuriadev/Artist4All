import { Component, ElementRef, OnInit } from '@angular/core';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { Publication } from 'src/app/core/models/publication';
import { UserService } from 'src/app/core/services/user.service';
import { Router } from '@angular/router';
import { PublicationService } from 'src/app/core/services/publication.service';
import {
  MatSnackBar,
  MatSnackBarHorizontalPosition,
  MatSnackBarVerticalPosition,
} from '@angular/material/snack-bar';
import { FormGroup, FormControl, Validators, FormBuilder} from '@angular/forms';

import { ViewChild } from '@angular/core';
@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css'],
  providers: [UserService],
})
export class HomeComponent implements OnInit {
  @ViewChild('imgPublication')
  inputImgs: ElementRef;

  constructor(
    private _sessionService: SessionService,
    private _publicationService: PublicationService,
    private _router: Router,
    private _snackBar: MatSnackBar,
    private _formBuilder: FormBuilder
    ) {}

  user = this._sessionService.getCurrentUser();
  id = this.user.id;
  token = this._sessionService.getCurrentToken();

  ngOnInit(): void {}

  images = [];
  imgToUpload: FileList = null;
  addImgPublication(imgPublication: FileList) {
    this.images = [];
    this.imgToUpload = imgPublication;
    if (imgPublication && imgPublication[0]) {
      var filesAmount = imgPublication.length;
      for (let i = 0; i < filesAmount; i++) {
        var reader = new FileReader();
        reader.onload = (event:any) => {
          this.images.push(event.target.result);
        }
        reader.readAsDataURL(imgPublication[i]);
      }
    }
  }

  removeSelectedImgs() {
    this.images = [];
    this.imgToUpload = null;
    this.inputImgs.nativeElement.value = null;
  }

  bodyPublication: string = '';
  createPublication() {
    this.message = 'Publicación creada.';
    this._publicationService.create(
      new Publication(null, this.user, this.imgToUpload, this.bodyPublication, null, 0, 0, 0, 0)).subscribe(
        (result) => {
          this.openSnackBar(this.message);
          this._router.navigate(['/home']);
        },
        (error) => {
          console.log(error);
    });
  }

  message: string;
  horizontalPosition: MatSnackBarHorizontalPosition = 'right';
  verticalPosition: MatSnackBarVerticalPosition = 'bottom';
  openSnackBar(message: string) {
    this._snackBar.open(message, 'OK', { duration: 2000, horizontalPosition: this.horizontalPosition, verticalPosition: this.verticalPosition });
  }

}
