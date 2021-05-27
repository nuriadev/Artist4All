import { Component, ElementRef, OnInit } from '@angular/core';
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
import { ViewChild } from '@angular/core';
@Component({
  selector: 'app-edit-publication',
  templateUrl: './edit-publication.component.html',
  styleUrls: ['./edit-publication.component.css'],
})
export class EditPublicationComponent implements OnInit {
  @ViewChild('imgPublication')
  inputImgs: ElementRef;

  constructor(
    private _publicationService: PublicationService,
    private _sessionService: SessionService,
    private _activeRoute: ActivatedRoute,
    private _location: Location,
    private _snackBar: MatSnackBar
  ) {}

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();

  id: number;
  images = [];
  imgToUpload: FileList = null;
  showingImgHint: boolean = false;
  addImgPublication(imgPublication: FileList) {
    this.imgsReceived = [];
    this.images = [];
    if (imgPublication !== null) {
      for (var i = 0; i < imgPublication.length; i++) {
        let file = imgPublication.item(i);
        let allowed = ['image/gif', 'image/png', 'image/jpg', 'image/jpeg'];
        if (!allowed.includes(file.type)) this.showingImgHint = true;
        else this.showingImgHint = false;
      }
      if (!this.showingImgHint) {
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
    }
  }

  removedImgs: number = 0;
  removeSelectedImgs() {
    this.images = [];
    this.showingImgHint = false;
    this.imgsReceived = [];
    this.imgToUpload = null;
    this.inputImgs.nativeElement.value = null;
    this.removedImgs = 1;
  }

  bodyPublication: string = '';
  n_likes: number;
  n_comments: number;
  isEdited: number;
  imgsReceived = [];

  miPublication;
  id_publication: string = '';
  ngOnInit(): void {
    this._activeRoute.paramMap.subscribe(
      (params) => {
        this.id_publication = params.get('id_publication');
        this._publicationService.getPublicationById(this.user.id, parseInt(this.id_publication)).subscribe(
          (result) => {
            this.miPublication = result;
            this.id = this.miPublication.id;
            this.user = this.miPublication.user;
            this.bodyPublication = this.miPublication.bodyPublication;
            this.n_likes = this.miPublication.n_likes;
            this.n_comments = this.miPublication.n_comments;
            this.isEdited = this.miPublication.isEdited;
            if (result.imgsPublication != null) {
              for (let i = 0; i < result.imgsPublication.length; i++) {
                let file = result.imgsPublication[i];
                this.imgsReceived.push(file.imgPublication);
              }
            }
          },
          (error) => {
            console.log(error);
          }
        );
    }, (error) => {
      console.log(error);
    });
  }

  contador: number = 255;
  showingBodyHint: boolean = false;
  countCharacters(event) {
    this.contador = 255;
    this.contador -= event.target.value.length;
    if (event.target.value.length > 255) this.showingBodyHint = true;
    else this.showingBodyHint = false;
  }

  editPublication() {
    this.message = "Publicación editada.";
    this._publicationService.edit(new Publication(this.id, this.user, this.imgToUpload, this.bodyPublication, null, this.n_likes, this.n_comments, 0, 1), this.removedImgs).subscribe(
        (result) => {
          setTimeout(() => this.openSnackBar(this.message), 1200);
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
