import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Publication } from 'src/app/core/models/publication';
import { PublicationService } from 'src/app/core/services/publication.service';
import { SessionService } from 'src/app/core/services/session.service';
import {
  MatSnackBar,
  MatSnackBarHorizontalPosition,
  MatSnackBarVerticalPosition,
} from '@angular/material/snack-bar';

@Component({
  selector: 'app-create-publication',
  templateUrl: './create-publication.component.html',
  styleUrls: ['./create-publication.component.css'],
})
export class CreatePublicationComponent implements OnInit {
  constructor(
    private _sessionService: SessionService,
    private _publicationService: PublicationService,
    private _router: Router,
    private _snackBar: MatSnackBar
  ) {}

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();

  ngOnInit(): void {}

  imgToUpload: FileList = null;
  addImgPublication(imgPublication: FileList) {
    this.imgToUpload = imgPublication;
  }

  bodyPublication: string = '';
  createPublication() {
    this.message = 'Publicación creada.';
    this.openSnackBar(this.message);
    this._publicationService.create(
      this.user.id,
      new Publication(null, this.user, this.imgToUpload, this.bodyPublication, null, 0, 0, 0, 0),
      this.token).subscribe(
        (result) => {
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
