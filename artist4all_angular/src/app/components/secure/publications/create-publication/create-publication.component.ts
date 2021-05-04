import { Component, ElementRef, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Publication } from 'src/app/core/models/publication';
import { PublicationService } from 'src/app/core/services/publication.service';
import { SessionService } from 'src/app/core/services/session.service';
import {
  MatSnackBar,
  MatSnackBarHorizontalPosition,
  MatSnackBarVerticalPosition,
} from '@angular/material/snack-bar';
import { ViewChild } from '@angular/core';
import { FormGroup, FormControl, Validators, FormBuilder} from '@angular/forms';

@Component({
  selector: 'app-create-publication',
  templateUrl: './create-publication.component.html',
  styleUrls: ['./create-publication.component.css'],
})
export class CreatePublicationComponent implements OnInit {
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



