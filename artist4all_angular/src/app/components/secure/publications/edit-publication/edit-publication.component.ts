import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Publication } from 'src/app/core/models/publication';
import { PublicationService } from 'src/app/core/services/publication.service';
import { SessionService } from 'src/app/core/services/session.service';
import { Location } from '@angular/common';
import {
  MatSnackBar,
  MatSnackBarHorizontalPosition,
  MatSnackBarVerticalPosition,
} from '@angular/material/snack-bar';
@Component({
  selector: 'app-edit-publication',
  templateUrl: './edit-publication.component.html',
  styleUrls: ['./edit-publication.component.css'],
})
export class EditPublicationComponent implements OnInit {
  constructor(
    private _publicationService: PublicationService,
    private _sessionService: SessionService,
    private _router: ActivatedRoute,
    private _activeRoute: ActivatedRoute,
    private _location: Location,
    private _snackBar: MatSnackBar
  ) {}

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();

  id: number;
  id_user: number;
  imgToUpload: FileList = null;
  addImgPublication(imgPublication: FileList) {
    this.imgToUpload = imgPublication;
  }
  bodyPublication: string = '';
  n_likes: number;
  n_comments: number;
  isEdited: number;

  miPublication: Publication;
  id_publication: string = '';
  ngOnInit(): void {
    this._activeRoute.paramMap.subscribe(
      (params) => {
        this.id_publication = params.get('id_publication');
        this._publicationService.getPublicationById(this.user.id, parseInt(this.id_publication), this.token).subscribe(
          (result) => {
            this.miPublication = result;
            this.id = this.miPublication.id;
            this.user = this.miPublication.user;
            this.bodyPublication = this.miPublication.bodyPublication;
            this.n_likes = this.miPublication.n_likes;
            this.n_comments = this.miPublication.n_comments;
            this.isEdited = this.miPublication.isEdited;
          },
          (error) => {
            console.log(error);
          }
        );
    }, (error) => {
      console.log(error);
    });
  }

  editPublication() {
    this.message = "Publicación editada.";
    this.openSnackBar(this.message);
    this._publicationService.edit(
      new Publication(this.id, this.user, this.imgToUpload, this.bodyPublication, null, this.n_likes, this.n_comments, 0, 1),
      this.token).subscribe(
        (result) => {
          this.redirectBack();
        }, (error) => {
          console.log(error);
    });
  }

  redirectBack() {
    this._location.back();
  }

  message: string;
  horizontalPosition: MatSnackBarHorizontalPosition = 'right';
  verticalPosition: MatSnackBarVerticalPosition = 'bottom';
  openSnackBar(message: string) {
    this._snackBar.open(message, 'OK', { duration: 2000, horizontalPosition: this.horizontalPosition, verticalPosition: this.verticalPosition });
  }
}
