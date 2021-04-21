import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { error } from 'protractor';
import { Publication } from 'src/app/core/models/publication';
import { User } from 'src/app/core/models/user';
import { CommentService } from 'src/app/core/services/comment.service';
import { PublicationService } from 'src/app/core/services/publication.service';
import { SessionService } from 'src/app/core/services/session.service';
import { Comment } from '../../../../core/models/comment';

@Component({
  selector: 'app-comment',
  templateUrl: './comment.component.html',
  styleUrls: ['./comment.component.css']
})
export class CommentComponent implements OnInit {

  constructor(
    private _sessionService: SessionService,
    private _commentService: CommentService,
    private _publicationService: PublicationService,
    private _formBuilder: FormBuilder,
    private _activeRoute: ActivatedRoute,
    ) { }

  id: number;
  name: string;
  surname1: string;
  surname2: string;
  email: string;
  username: string;
  password: string;
  imgAvatar: FileList;
  aboutMe: string;
  isPrivate: number;

  user: User;
  token = this._sessionService.getCurrentToken();

  publication: Publication;
  comments: Array<Comment>;
  subcomments: Array<Comment>;
  commentForm: FormGroup;
  id_publication: string = "";
  ngOnInit(): void {
    this.user = this._sessionService.getCurrentUser();
    this.id = this.user.id;
    this.name = this.user.name;
    this.surname1 = this.user.surname1;
    this.surname2 = this.user.surname2;
    this.email = this.user.email;
    this.username = this.user.username;
    this.password = this.user.password;
    this.imgAvatar = this.user.imgAvatar;
    this.aboutMe = this.user.aboutMe;
    this.isPrivate = this.user.isPrivate;
    this._activeRoute.paramMap.subscribe((params) => {
    this.id_publication = params.get('id_publication');
      this._commentService.getPublicationComments(this.id, parseInt(this.id_publication)).subscribe(
      (result) => {
        if (result != null) {
          result.forEach((comment) => {
            comment.comment_date = this.adaptDateOfComment(comment.comment_date);
          });
          this.comments = result;
        }
      }, (error) => {
        console.log(error);
      });
    });
    this.commentForm = this._formBuilder.group({
      id: [null],
      user: [this.user],
      bodyComment: ['', []],
      // bodyComment: ['', [Validators.required, Validators.minLength(1), Validators.maxLength(255)]],
      isEdited: [0],
      comment_date: [null],
      id_publication: [parseInt(this.id_publication)],
      id_comment_reference: [null],
      subcomments: [null]
    });
  }

  showingForm: boolean = false;
  toggleResponseForm(index: number): void {
    let formSubcommentResponseForm = document.getElementById(index + 'formSubcommentResponseForm');
    let formResponseContainer = document.getElementById(index + 'formResponseContainer');
    let subcommentContainer = document.getElementById(index + 'subcommentContainer');
    if (!this.showingForm) {
      formResponseContainer.style.display = 'block';
      this.showingForm = true;
      if (subcommentContainer) subcommentContainer.style.display = 'none';
      this.showingSubcomments = false;
      if (formSubcommentResponseForm) formSubcommentResponseForm.style.display = 'none';
      this.showingSubcommentForm = false;
    } else {
      formResponseContainer.style.display = 'none';
      this.showingForm = false;
    }
  }

  showingSubcomments: boolean = false;
  //TODO: poner un spinner de carga antes de mostrar o sacar algun div
  toggleSubcomments(index: number) {
    this.comments.forEach((comment, indexArray) => {
      if (document.getElementById(indexArray + 'subcommentContainer')) {
        document.getElementById(indexArray + 'subcommentContainer').style.display = 'none';
        this.showingSubcomments = false;
      }
      if (document.getElementById(indexArray + 'noSubcommentContainer')) {
        document.getElementById(indexArray + 'noSubcommentContainer').style.display = 'none';
        this.showingSubcomments = false;
      }
    });
    let formResponseContainer = document.getElementById(index + 'formResponseContainer');
    let subcommentContainer = document.getElementById(index + 'subcommentContainer');
    let noSubcommentContainer = document.getElementById(index + 'noSubcommentContainer');
    this._commentService.getCommentSubcomments(this.user.id, this.comments[index].id_publication, this.comments[index].id).subscribe(
      (result) => {
        if (result != null) {
          result.forEach((subcomment) => {
            subcomment.comment_date = this.adaptDateOfComment(subcomment.comment_date);
          });
          this.subcomments = result;
          if (!this.showingSubcomments) {
            subcommentContainer.style.display = 'block';
            this.showingSubcomments = true;
            formResponseContainer.style.display = 'none';
            this.showingForm = false;
          } else {
            subcommentContainer.style.display = 'none';
            this.showingSubcomments = false;
          }
        } else {
          if (!this.showingSubcomments) {
            noSubcommentContainer.style.display = 'block';
            this.showingSubcomments = true;
            formResponseContainer.style.display = 'none';
            this.showingForm = false;
          } else {
            noSubcommentContainer.style.display = 'none';
            this.showingSubcomments = false;
          }
        }
      }, (error) => {
        console.log(error);
    });
  }

  showingSubcommentForm: boolean = false;
  toggleSubcommentResponseForm(index: number): void {
    this.comments.forEach((comment, indexArray) => {
      if (document.getElementById(indexArray + 'formSubcommentResponseForm')) {
        document.getElementById(indexArray + 'formSubcommentResponseForm').style.display = 'none';
        this.showingSubcommentForm = false;
      }
    });
    let formSubcommentResponseForm = document.getElementById(index + 'formSubcommentResponseForm');
    let formResponseContainer = document.getElementById(index + 'formResponseContainer');
    if (!this.showingSubcommentForm) {
      formSubcommentResponseForm.style.display = 'block';
      this.showingSubcommentForm = true;
      formResponseContainer.style.display = 'none';
      this.showingForm = false;
    } else {
      formSubcommentResponseForm.style.display = 'none';
      this.showingSubcommentForm = false;
    }
  }



  isValidFormSubmitted = null;
  //TODO: Contador de carácteres


  get bodyComment() { return this.commentForm.get('bodyComment'); }

  newComment: Comment;
  postComment() {
    this.isValidFormSubmitted = false;
    if (this.commentForm.invalid) {
      return;
    }
    this.isValidFormSubmitted = true;
    let comment: Comment = this.commentForm.value;
    this._commentService.postComment(comment).subscribe(
      (result) => {
        this.newComment = result;
        this.comments.unshift(this.newComment);
        this.commentForm.reset();
        this.commentForm.controls['user'].setValue(this.user);
        this.commentForm.controls['id_publication'].setValue(parseInt(this.id_publication));
        this.commentForm.controls['isEdited'].setValue(0);
        this.commentForm.controls['id_comment_reference'].setValue(null);
      }, (error) => {
        console.log(error);
    });
  }

  adaptDateOfComment(upload_date: string) {
    const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    let numberDay = new Date(upload_date).getDay();
    let nameDay = days[numberDay];
    let nameMonth = months[new Date(upload_date).getMonth()];
    let datetime = upload_date.split(' ');
    let date = datetime[0].split('-');
    let time = datetime[1].split(':');
    let year = date[0];
    let month = date[1];
    let day = date[2];
    let hourAndMin = time[0] + ':' + time[1];
    let adaptedDate = nameDay + ', ' + day + ' de ' + nameMonth + ' de ' + year + ', a las ' + hourAndMin;
    return adaptedDate;
  }

}
